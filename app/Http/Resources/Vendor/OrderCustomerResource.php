<?php

namespace App\Http\Resources\Vendor;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderCustomerResource extends JsonResource
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
            'full_name' => $this['full_name'],
            'email' => $this['email'],
            'image' => $this['image'],
        ];
    }
}
