<?php

namespace App\Services\GlobalService;

use App\Exceptions\HttpException;
use App\Http\Requests\Vendor\Auth\Password\ChangePasswordRequest;
use App\Http\Requests\Vendor\Auth\Password\PasswordOtpRequest;
use App\Http\Requests\Vendor\Auth\Password\ResetPasswordRequest;
use App\Models\Admin\Admin;
use App\Models\Customer\User;
use App\Models\Utility\OtpVerification;
use App\Models\Vendor\Vendor;
use App\Services\Utility\OtpVerificationService;
use App\Traits\JsonResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Throwable;

class PasswordService
{
    use JsonResponseTrait;

    public function __construct(protected OtpVerificationService $otpVerificationService)
    {
    }

    /**
     * @param mixed $model
     * @param Admin|User|Vendor $user
     * @param string $purpose
     * @param string $recipient
     * @param string $type
     * @return JsonResponse
     * @throws Exception
     */
    public function requestPasswordOtp(
        mixed $model,
        Admin|User|Vendor $user,
        string $purpose,
        string $recipient,
        string $type,
    ): JsonResponse {

        $otp = $this->otpVerificationService->sendOtp(
            $model,
            $user,
            $purpose,
            $recipient,
            $type,
        );

        return $this->successResponse(['otp_verification_id' => $otp->id], 'Otp sent successfully');
    }

    /**
     * @param mixed $model
     * @param Admin|User|Vendor $user
     * @param string $code
     * @param string $purpose
     * @param string $recipient
     * @param string $type
     * @param int $otpVerificationId
     * @param mixed $password
     * @return JsonResponse
     * @throws HttpException|Throwable
     */
    public function resetPassword(
        mixed $model,
        Admin|User|Vendor $user,
        string $code,
        string $purpose,
        string $recipient,
        string $type,
        int $otpVerificationId,
        mixed $password
    ): JsonResponse {

        $this->otpVerificationService->verifyOtp(
            $model,
            $user,
            $code,
            $purpose,
            $recipient,
            $type,
            $otpVerificationId
        );

        $this->updatePassword($user, $password);

        return $this->success('Password updated successfully.');
    }

    /**
     * @param Admin|User|Vendor $user
     * @param mixed $password
     */
    protected function updatePassword(Admin|User|Vendor $user, mixed $password): void
    {
        $user->update([
            'password' => Hash::make($password)
        ]);
    }

    /**
     * @param Admin|User|Vendor $user
     * @param mixed $password
     * @return JsonResponse
     */
    public function changePassword(Admin|User|Vendor $user, mixed $password): JsonResponse
    {
        $this->updatePassword($user, $password);
        return $this->success('Password changed successfully.');
    }
}
