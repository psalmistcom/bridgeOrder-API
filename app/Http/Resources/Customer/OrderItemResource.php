<?php

namespace App\Http\Resources\Customer;

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
            $this->mergeWhen(is_null($this['variant']), [
                'item' => OrderMenuResource::make($this['menu']),
            ]),
            $this->mergeWhen(!is_null($this['variant']), [
                'item' => OrderVariantResource::make($this['variant']),
            ]),
            'quantity' => $this['quantity']
        ];
    }
}
