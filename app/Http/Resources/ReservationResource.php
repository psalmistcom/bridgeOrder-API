<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
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
            $this->mergeWhen((bool)$request->user()->tokenCan('customer-access'), [
                'restaurant' => $this['restaurant'],
            ]),
            $this->mergeWhen((bool)$request->user()->tokenCan('vendor-access'), [
                'customer' => $this['user'],
            ]),
            'number_of_guests' => $this['number_of_guests'],
            'date' => $this['date'],
            'check_in' => $this['check_in'],
            'status' => $this['status'],
            'reservation_type' => $this['reservation_type'],
            'special_request' => $this['special_request']
        ];
    }
}
