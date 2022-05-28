<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Resources\Vendor\TransactionResource;
use App\Models\Finance\Transaction;
use App\Models\Vendor\Restaurant;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function index(Request $request): JsonResponse
    {
        return $this->datatableResponse(
            Transaction::whereTransactableType(Restaurant::class)
            ->whereTransactableId($request->user()->restaurant->id)->latest(),
            TransactionResource::class
        );
    }
}
