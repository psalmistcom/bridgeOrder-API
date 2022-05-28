<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\TransactionResource;
use App\Models\Finance\Transaction;
use App\Models\Vendor\Restaurant;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TransactionController extends Controller
{
    /**
     * @return BinaryFileResponse|JsonResponse
     * @throws Exception
     */
    public function index(): BinaryFileResponse|JsonResponse
    {
        return $this->datatableResponse(
            Transaction::whereTransactableType(Restaurant::class)->latest(),
            TransactionResource::class
        );
    }
}
