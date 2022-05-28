<?php

namespace App\Http\Controllers\API\Customer;

use App\Enum\PaymentMethod;
use App\Enum\Status;
use App\Enum\TransactionCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Reservation\CreateReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Customer\User;
use App\Models\Vendor\Reservation;
use App\Models\Vendor\Restaurant;
use App\Services\Finance\PaymentCardService;
use App\Services\Finance\WalletService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ReservationController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return $this->successResponse(
            ReservationResource::collection($request->user()->reservations)
        );
    }

    /**
     * @param CreateReservationRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function makeReservation(CreateReservationRequest $request): JsonResponse
    {
        $restaurant = Restaurant::whereId($request->input('restaurant_id'))->first();
        try {
            DB::beginTransaction();
            match ($request->input('payment_method')) {
                PaymentMethod::BRIDGE_CARD->value
                => PaymentCardService::payWithCard(
                    $request->user(),
                    $restaurant->reservation_price,
                    User::class,
                    $restaurant->name . ' reservation.',
                    $request->user()->id,
                    TransactionCategory::RESERVATION->value
                ),
                PaymentMethod::BRIDGE_WALLET->value
                => WalletService::withdrawFromWallet(
                    $request->user(),
                    $restaurant->reservation_price,
                    User::class,
                    $restaurant->name . ' reservation.',
                    $request->user()->id,
                    TransactionCategory::RESERVATION->value
                ),
                default => $this->error('Payment error')
            };
                $vendor = Reservation::create([
                    'restaurant_id' => $restaurant->id,
                    'user_id' => $request->user()->id,
                    'number_of_guests' => $request->input('number_of_guests'),
                    'date' => $request->input('date'),
                    'check_in' => $request->input('check_in'),
                    'reservation_type' => $request->input('reservation_type'),
                    'special_request' => $request->input('special_request'),
                    'status' => Status::PAID->value,
                    'payment_method' => $request->input('payment_method')
                ]);
            DB::commit();
            return $this->successResponse($vendor, 'Reservation Booked Successfully.');
        } catch (Exception $e) {
            DB::rollback();
            return $this->fatalErrorResponse($e);
        }
    }
}
