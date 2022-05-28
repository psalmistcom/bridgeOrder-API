<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\Wallet\RequestWithdrawalRequest;
use App\Http\Resources\WithdrawalRequestResource;
use App\Models\Utility\OtpVerification;
use App\Models\Vendor\Restaurant;
use App\Models\Vendor\Vendor;
use App\Models\Vendor\WithdrawalRequest;
use App\Services\Utility\OtpVerificationService;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class WalletController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return $this->successResponse(
            $request->user()->restaurant->wallet
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse|BinaryFileResponse
     * @throws Exception
     */
    public function withdrawalRequests(Request $request): BinaryFileResponse|JsonResponse
    {
        return $this->datatableResponse(
            WithdrawalRequest::whereRestaurantId($request->user()->restaurant->id),
            WithdrawalRequestResource::class
        );
    }

    public function withdrawalRequestOtp(Request $request)
    {
//        dd($request->user()->restaurant->pendingWithdrawalRequest);
        if (!is_null($request->user()->restaurant->pendingWithdrawalRequest)) {
            return $this->error(
                'You already requested for withdrawal. Kindly hold on for approval. ',
            );
        }
        $response['otp_verification_id'] = self::sendOtp(
            $request->user(),
            OtpVerification::PURPOSE_WITHDRAWAL_REQUEST,
            $request->user()['email'],
            'mail'
        );

        return $this->successResponse($response, 'Otp for withdrawal request sent successfully.');
    }

    public static function sendOtp(
        Vendor|Authenticatable $user,
        string $purpose,
        string $recipient,
        string $route
    ): mixed {
        $otp = app(OtpVerificationService::class)->sendOtp(
            Vendor::class,
            $user,
            $purpose,
            $recipient,
            $route,
            true
        );
        return $otp->id;
    }

    /**
     * @param RequestWithdrawalRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function requestWithdrawal(RequestWithdrawalRequest $request): JsonResponse
    {
        if ($request->user()->restaurant->wallet->balance < 1) {
            return $this->error('Your current balance is too low for a withdrawal request.');
        }

        if ($request->user()->restaurant->wallet->balance < $request->input('amount')) {
            return $this->error('Amount entered cannot be greater than your current wallet balance.');
        }
        try {
            DB::beginTransaction();
                app(OtpVerificationService::class)->verifyOtp(
                    Vendor::class,
                    $request->user(),
                    $request->input('code'),
                    OtpVerification::PURPOSE_WITHDRAWAL_REQUEST,
                    $request->user()['email'],
                    'mail',
                    $request->input('otp_verification_id'),
                );
            // also check if a pending request exists to prevent more than one pending request at a time

                WithdrawalRequest::create([
                    'restaurant_id' => $request->user()->restaurant->id,
                    'amount' => $request->input('amount')
                ]);
            DB::commit();
            return $this->success('Your withdrawal request has been sent. Kindly hold on for approval.');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->error($e->getMessage());
        }
    }
}
