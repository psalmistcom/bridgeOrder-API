<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\Order\UpdateOrderStatus;
use App\Http\Resources\Vendor\OrderResource;
use App\Models\Vendor\Order;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function index(Request $request): JsonResponse
    {
        return $this->datatableResponse(
            Order::whereRestaurantId($request->user()->restaurant_id)->latest(),
            OrderResource::class
        );
    }

    /**
     * @param UpdateOrderStatus $request
     * @param Order $order
     * @return JsonResponse
     */
    public function status(UpdateOrderStatus $request, Order $order): JsonResponse
    {
        $order->update([
            'order_status' => $request->input('status')
        ]);
        return $this->success(
            'Order status successfully updated to ' . $request->input('status')
        );
    }
}
