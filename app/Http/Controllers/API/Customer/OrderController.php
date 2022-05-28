<?php

namespace App\Http\Controllers\API\Customer;

use App\Enum\ActivityType;
use App\Enum\PaymentMethod;
use App\Enum\Status;
use App\Enum\TransactionCategory;
use App\Enum\TransactionType;
use App\Exceptions\HttpException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Order\PlaceOrderRequest;
use App\Http\Resources\Customer\OrderResource;
use App\Models\Customer\User;
use App\Models\Utility\ActivityLog;
use App\Models\Utility\Configuration;
use App\Notifications\Vendor\OrderPlacedNotification;
use App\Models\Vendor\{Order, OrderMenu, Restaurant};
use App\Services\Finance\{PaymentCardService, TransactionService, WalletService};
use Exception;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\DB;
use Throwable;

class OrderController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return $this->successResponse(
            OrderResource::collection(Order::whereUserId($request->user()->id)->get())
        );
    }

    /**
     * @param PlaceOrderRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function placeOrder(PlaceOrderRequest $request): JsonResponse
    {
//        $request->whenHas('name', function ($input) {
//            //
//        });
        try {
            $user = User::find($request->user()->id);
            $restaurant = Restaurant::find($request->input('restaurant_id'));
            $latestOrder = Order::whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])
                ->orderBy('created_at', 'DESC')->first();
            $orderNumber = is_null($latestOrder)
                ? $this->generateOrderNumber(0)
                : $this->generateOrderNumber($latestOrder->order_number);
            $itemCount = count($request->input('items'));

            DB::beginTransaction();
                $order = Order::create([
                    'order_number' => $orderNumber,
                    'restaurant_id' => $request->input('restaurant_id'),
                    'user_id' => $user->id,
                    'order_type' => $request->input('order_type'),
                    'payment_method' =>  $request->input('payment_method'),
                    'item_count' => $itemCount,
                ]);

            foreach ($request->input('items') as $item) {
                OrderMenu::create([
                    'order_id' => $order['id'],
                    'menu_id' => $item['menu_id'],
                    'variant_id' => $item['variant_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'price' => $item['quantity'] * $item['price'],
                ]);
            }

                $orderMenu = OrderMenu::whereOrderId($order->id)->get();
                $totalPrice = $orderMenu->sum('price');

                $this->makePayment($totalPrice, $request->input('payment_method'), $user, $order, $restaurant);

                $vendorFee = (float) $totalPrice - ($totalPrice * Configuration::value('vendor_commission_fee'));
                $order->total_price = $totalPrice;
                $order->vendor_fee = $vendorFee;
                $order->payment_status = Status::PAID->value;
                $order->save();

                ActivityLog::log(
                    User::class,
                    $user['id'],
                    $order,
                    ActivityType::USER_PLACE_ORDER->value,
                    'order placed by customer',
                    $restaurant->id
                );

                WalletService::topUpWallet(
                    Restaurant::class,
                    $restaurant,
                    $order->vendor_fee,
                    Status::SUCCESS->value,
                    TransactionType::WALLET_TOP_UP->value,
                    'Order (' . $order->order_number . ') placed by customer (' . $user->full_name . ')',
                    '',
                    $user->id,
                    TransactionCategory::ORDER->value
                );
            DB::commit();
            $restaurant->notify(new OrderPlacedNotification($user, $order));
            return $this->successResponse($order, 'Order placed successfully');
        } catch (Exception $e) {
            DB::rollback();
            return $this->fatalErrorResponse($e);
        }
    }

    /**
     * @param string|int $id
     * @return string|int
     */
    protected function generateOrderNumber(string|int $id): string|int
    {
        return str_pad($id + 1, 4, "0", STR_PAD_LEFT);
    }

    /**
     * @param float $amount
     * @param string $method
     * @param User $user
     * @param Order $order
     * @param Restaurant $restaurant
     * @throws HttpException
     */
    protected function makePayment(
        float $amount,
        string $method,
        User $user,
        Order $order,
        Restaurant $restaurant
    ): void {
        match ($method) {
            PaymentMethod::BRIDGE_WALLET->value
            => WalletService::withdrawFromWallet(
                $user,
                $amount,
                User::class,
                'Order (' . $order->order_number . ') placed with ' . $restaurant->name,
                $user->id,
                TransactionCategory::ORDER->value
            ),
            PaymentMethod::BRIDGE_CARD->value
            => PaymentCardService::payWithCard(
                $user,
                $amount,
                User::class,
                'Order (' . $order->order_number . ') placed with ' . $restaurant->name,
                $user->id,
                TransactionCategory::ORDER->value
            ),
            default => $this->error('Payment error')
        };
    }
}
