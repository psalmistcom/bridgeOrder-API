<?php

namespace App\Http\Resources\Customer;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this['id'],
            'order_number' => $this['order_number'],
            'total_price' => $this['total_price'],
            'order_status' => $this['order_status'],
            'order_type' => $this['order_type'],
            'payment_status' => $this['payment_status'],
            'payment_method' => $this['payment_method'],
            'restaurant' => OrderRestaurantResource::make($this['restaurant']),
            'items' => OrderItemResource::collection($this['items'])
        ];
    }
}
