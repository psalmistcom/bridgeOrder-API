<?php

namespace App\Http\Resources\Customer;

use App\Http\Resources\Finance\WalletResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class CustomerResource extends JsonResource
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
            'email_verified_at' => $this['email_verified_at'],
            'status' => $this['status'],'wallet' => WalletResource::make($this->whenLoaded('wallet')),
            'image' => $this['image'],
            'logged_in_at' => $this['logged_in_at'],
            'logged_out_at' => $this['logged_out_at'],
            'created_at' => $this['created_at'],
        ];
    }
}
