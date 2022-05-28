<?php

namespace App\Http\Resources\Vendor;

use App\Http\Resources\Finance\WalletResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantResource extends JsonResource
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
            'name' => $this['name'],
            'slug' => $this['slug'],
            'status' => $this['status'],
            'allow_reservation' => $this['allow_reservation'],
            'reservation_price' => $this['reservation_price'],
            'image' => $this['image'],
            'wallet' => WalletResource::make($this->whenLoaded('wallet')),
            'created_at' => $this['created_at'],
        ];
    }
}
