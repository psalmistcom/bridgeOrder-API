<?php

namespace App\Http\Resources\Vendor;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this['id'],
            'menu' => $this['menu'],
            'variant' => $this['variant'],
            'quantity' => $this['quantity']
        ];
    }
}
