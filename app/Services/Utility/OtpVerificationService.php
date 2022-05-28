<?php

namespace App\Services\Utility;

use App\Enum\Status;
use App\Events\RequestOtpEvent;
use App\Exceptions\HttpException;
use App\Models\Admin\Admin;
use App\Models\Customer\User;
use App\Models\Utility\OtpVerification;
use App\Models\Vendor\Vendor;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class OtpVerificationService
{
    public function __construct(protected OtpVerification $otpVerification)
    {
    }

    /**
     * @param mixed $model
     * @param Admin|User|Vendor|Authenticatable $user
     * @param string $purpose
     * @param string $recipient
     * @param string $type
     * @param bool $event
     * @return OtpVerification
     * @throws Exception
     */
    public function sendOtp(
        mixed $model,
        Admin|User|Vendor|Authenticatable $user,
        string $purpose,
        string $recipient,
        string $type,
        bool $event = true
    ): OtpVerification {
        if (
            ! is_null($otpVerification = $this->otpExistsAndPending($model, $user, $purpose, $recipient))
            && ! $otpVerification->isExpired()
        ) {
            $event ? event(new RequestOtpEvent($otpVerification, $user)) : true;
            return $otpVerification;
        }

        $this->otpVerification->otpverifiable_type = $model;
        $this->otpVerification->otpverifiable_id = $user->id;
        $this->otpVerification->recipient = $recipient;
        $this->otpVerification->purpose = $purpose;
        $this->otpVerification->code = $this->generateOtp();
        $this->otpVerification->type = $type;
        $this->otpVerification['expires_at'] = now()->addHour();
        $this->otpVerification->save();

        $event ? event(new RequestOtpEvent($this->otpVerification, $user)) : true;
        return $this->otpVerification;
    }

    /**
     * @param mixed $model
     * @param Admin|User|Vendor|Authenticatable $user
     * @param string $purpose
     * @param string $recipient
     * @return OtpVerification|Builder|Model|object|null
     */
    private function otpExistsAndPending(
        mixed $model,
        Admin|User|Vendor|Authenticatable $user,
        string $purpose,
        string $recipient
    ) {
        return OtpVerification::query()
            ->whereHasMorph('otpverifiable', $model)
            ->whereStatus(Status::PENDING)
            ->wherePurpose($purpose)
            ->whereOtpverifiableId($user->id)
            ->whereRecipient($recipient)
            ->first();
    }

    /**
     * Verifies an otp validity using the parameters provided.
     * @param mixed $model
     * @param Admin|User|Vendor|Authenticatable $user
     * @param string $code
     * @param string $purpose
     * @param string $recipient
     * @param string $type
     * @param int $otpVerificationId
     * @return OtpVerification
     * @throws HttpException
     * @throws Throwable
     */
    public function verifyOtp(
        mixed $model,
        Admin|User|Vendor|Authenticatable $user,
        string $code,
        string $purpose,
        string $recipient,
        string $type,
        int $otpVerificationId,
    ): OtpVerification {

        $otpVerification = OtpVerification::query()
            ->whereHasMorph('otpverifiable', $model)
            ->whereOtpverifiableId($user->id)
            ->whereCode($code)
            ->wherePurpose($purpose)
            ->whereRecipient($recipient)
            ->whereType($type)
            ->find($otpVerificationId);

        if (!$otpVerification) {
            throw new HttpException('The otp entered is invalid');
        }

        $otpVerification->otpExpired(static function () {
            throw new HttpException('Otp expired. Please generate a new one');
        });

        $otpVerification->markAsVerified();

        return $otpVerification;
    }

    /**
     * Generates a random string to use as otp.
     * @return string
     * @throws Exception
     */
    public function generateOtp(): string
    {
        return (string) random_int(100000, 999999);
    }
}
