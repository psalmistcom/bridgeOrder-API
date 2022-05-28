<?php

namespace App\Http\Controllers\API\Customer;

use App\Exceptions\HttpException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Auth\Password\ChangePasswordRequest;
use App\Http\Requests\Customer\Auth\Password\PasswordOtpRequest;
use App\Http\Requests\Customer\Auth\Password\ResetPasswordRequest;
use App\Models\Customer\User;
use App\Models\Utility\OtpVerification;
use App\Services\GlobalService\PasswordService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class PasswordController extends Controller
{
    public function __construct(protected PasswordService $passwordService)
    {
    }

    /**
     * @param PasswordOtpRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function requestPasswordOtp(PasswordOtpRequest $request): JsonResponse
    {
        try {
            $customer = User::whereEmail($request->input('email'))->first();
            return $this->passwordService->requestPasswordOtp(
                User::class,
                $customer,
                OtpVerification::PURPOSE_PASSWORD_RESET,
                $request->input('email'),
                OtpVerification::TYPE_MAIL
            );
        } catch (Exception $e) {
            return $this->fatalErrorResponse($e);
        }
    }

    /**
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     * @throws HttpException|Throwable
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $customer = User::whereEmail($request->input('email'))->first();
            DB::beginTransaction();
                $reset = $this->passwordService->resetPassword(
                    User::class,
                    $customer,
                    $request->input('code'),
                    OtpVerification::PURPOSE_PASSWORD_RESET,
                    $request->input('email'),
                    OtpVerification::TYPE_MAIL,
                    $request->input('otp_verification_id'),
                    $request->input('password')
                );
            DB::commit();
            return $reset;
        } catch (Exception $e) {
            DB::rollback();
            return $this->fatalErrorResponse($e);
        }
    }

    /**
     * @param ChangePasswordRequest $request
     * @return JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        try {
            $currentPassword = $request->input('current_password');
            $newPassword = $request->input('password');
            $check = Hash::check($currentPassword, $request->user()->password);

            return match ($check) {
                false => $this->error('Incorrect password provided'),
                default => $this->passwordService->changePassword($request->user(), $newPassword)
            };
        } catch (Exception $e) {
            return $this->fatalErrorResponse($e);
        }
    }
}
