<?php

namespace App\Http\Resources\Vendor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            $this->mergeWhen(!is_null($this['wallet_id']), [
                'wallet_id' => $this['wallet_id'],
            ]),
            'description' => $this['description'],
            'amount' => $this['amount'],
            'status' => $this['status'],
            'type' => $this['type'],
            $this->mergeWhen(!is_null($this['payment_method']), [
                'payment_method' => $this['payment_method'],
            ]),
            $this->mergeWhen(!is_null($this['category']), [
                'category' => $this['category'],
            ]),
            'customer' => $this['customer'],
            'created_at' => $this['created_at'],
        ];
    }
}
