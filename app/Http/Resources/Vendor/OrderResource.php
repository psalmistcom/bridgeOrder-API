<?php

namespace App\Http\Resources\Vendor;

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
            'order_status' => $this['order_status'],
            'order_type' => $this['order_type'],
            'payment_status' => $this['payment_status'],
            'total_price' => $this['total_price'],
            'vendor_fee' => $this['vendor_fee'],
            'item_count' => $this['item_count'],
            'customer' => OrderCustomerResource::make($this['user']),
            'items' => $this['items'],
            'created_at' => $this['created_at'],
        ];
    }
}
