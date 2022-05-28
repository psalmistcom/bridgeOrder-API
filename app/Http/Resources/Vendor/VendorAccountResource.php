<?php

namespace App\Http\Resources\Vendor;

use App\Http\Resources\RoleResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class VendorAccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request): array|JsonSerializable|Arrayable
    {
        return [
            'id' => $this['id'],
            'full_name' => $this['full_name'],
            'email' => $this['email'],
            'image' => $this['image'],
            'role' => RoleResource::make($this->whenLoaded('role')),
            'restaurant' => RestaurantResource::make($this->whenLoaded('restaurant')),
            'created_at' => $this['created_at'],
        ];
    }
}
