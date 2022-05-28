<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReservationResource;
use App\Models\Vendor\Reservation;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function index(Request $request): JsonResponse
    {
        return $this->datatableResponse(
            Reservation::whereRestaurantId($request->user()->restaurant->id)->latest(),
            ReservationResource::class
        );
    }
}
