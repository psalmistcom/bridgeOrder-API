<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WithdrawalRequest\ProcessWithdrawalRequest;
use App\Http\Resources\WithdrawalRequestResource;
use App\Models\Vendor\Restaurant;
use App\Models\Vendor\WithdrawalRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class WalletController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse|BinaryFileResponse
     * @throws Exception
     */
    public function withdrawalRequests(Request $request): BinaryFileResponse|JsonResponse
    {
        return $this->datatableResponse(
            WithdrawalRequest::latest(),
            WithdrawalRequestResource::class
        );
    }

//    public function processRequest(ProcessWithdrawalRequest $request)
//    {
//        try {
//            $withdrawalRequest = WithdrawalRequest::find($request->input('withdrawal_request_id'));
//            $restaurant = Restaurant::find($withdrawalRequest->restaurant_id);
////            dd($withdrawalRequest,$restaurant);
//            DB::beginTransaction();
//
//            DB::commit();
//        } catch (Exception $e) {
//            DB::rollback();
//            return $this->fatalErrorResponse($e);
//        }
//    }
}
