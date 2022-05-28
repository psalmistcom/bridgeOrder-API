<?php

namespace App\Http\Resources;

use \App\Http\Resources\Vendor\RestaurantResource;
use App\Http\Resources\Vendor\MenuResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavouriteResource extends JsonResource
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
            'menu' => MenuResource::make($this['menu']),
            'restaurant' => RestaurantResource::make($this['restaurant']),
        ];
    }
}
