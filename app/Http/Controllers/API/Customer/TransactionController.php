<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Customer\TransactionResource;
use App\Models\Finance\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return $this->successResponse(
            TransactionResource::collection($request->user()->transactions)
        );
    }

    public function wallet(Request $request): JsonResponse
    {
        return $this->successResponse(
            TransactionResource::collection(
                $request->user()->transactions->whereNotNull('wallet_id')
            )
        );
    }
}
