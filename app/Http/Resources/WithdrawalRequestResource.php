<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WithdrawalRequestResource extends JsonResource
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
            'restaurant' => RestaurantResource::make($this['restaurant']),
            'amount' => $this['amount'],
            'status' => $this['status'],
            'created_at' => $this['created_at']
        ];
    }
}
