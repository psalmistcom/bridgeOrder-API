<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\RoleResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class AdminResource extends JsonResource
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
            'role' => RoleResource::make($this->whenLoaded('role')),
            'image' => $this['image'],
            'logged_in_at' => $this['logged_in_at'],
            'logged_out_at' => $this['logged_out_at'],
            'created_at' => $this['created_at'],
        ];
    }
}
