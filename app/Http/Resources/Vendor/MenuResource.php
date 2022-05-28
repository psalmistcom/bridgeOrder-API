<?php

namespace App\Http\Resources\Vendor;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class MenuResource extends JsonResource
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
            'item_name' => $this['item_name'],
            'price' => $this['price'],
            'in_stock' => $this['in_stock'],
            'image' => $this['image'],
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'variants' => VariantResource::collection($this->whenLoaded('variants')),
            'added_by' => VendorResource::make($this->whenLoaded('vendor')),
            'created_at' => $this['created_at']
        ];
    }
}
