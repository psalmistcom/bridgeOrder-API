<?php

namespace App\Http\Controllers\API\Customer;

use App\Enum\ActivityType;
use App\Enum\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Auth\CustomerResendOtpRequest;
use App\Http\Requests\Customer\Auth\CustomerVerifyEmailRequest;
use App\Http\Requests\Customer\Auth\LoginRequest;
use App\Http\Requests\Customer\Auth\RegisterRequest;
use App\Http\Resources\Customer\CustomerResource;
use App\Models\Customer\User;
use App\Models\Utility\ActivityLog;
use App\Models\Utility\OtpVerification;
use App\Notifications\Customer\NewCustomerNotification;
use App\Services\Finance\WalletService;
use App\Services\Utility\OtpVerificationService;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
                $customer = User::create([
                    'full_name' => $request->input('full_name'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password')),
                ]);

                WalletService::createWallet($customer);

                $response['token'] = $customer->createToken('customer', ['customer-access'])->accessToken;
                $response['user'] = CustomerResource::make($customer);

                $customer->logged_in_at = now();
                $customer->save();

                ActivityLog::log(
                    User::class,
                    $customer['id'],
                    $customer,
                    ActivityType::USER_SIGN_UP->value,
                    'user account created',
                );
            DB::commit();
            $otp = $this->sendOtp($customer, false);
            $response['otp_verification_id'] = $otp->id;
            $customer->notify(new NewCustomerNotification($otp));
            return $this->successResponse($response, 'Registration successful');
        } catch (Exception $e) {
            DB::rollback();
            return $this->fatalErrorResponse($e);
        }
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $customer = User::query()->whereEmail($request->input('email'))
                ->with('wallet')
                ->first();

            if (!$customer || !Hash::check($request->input('password'), $customer->password)) {
                return $this->error('Invalid credentials provided');
            }

            $response['token'] =  $customer->createToken('customer', ['customer-access'])->accessToken;
            $response['user'] =  CustomerResource::make($customer);
            $customer->logged_in_at = now();
            $customer->save();

            ActivityLog::log(
                User::class,
                $customer['id'],
                $customer,
                ActivityType::USER_SIGN_IN->value,
                'user logged in',
            );

            return $this->successResponse($response, 'Login successful');
        } catch (Exception $e) {
            return $this->fatalErrorResponse($e);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function session(Request $request): JsonResponse
    {
        $customer = User::query()->with('wallet')
            ->find($request->user()->id);

        return response()->json([
            'status' => true,
            'message' => 'Authenticated',
            'user' => CustomerResource::make($customer),
        ]);
    }

    public function resendOtp(CustomerResendOtpRequest $request): JsonResponse
    {
        try {
            $otp = $this->sendOtp($request->user(), true);
            return $this->successResponse(['otp_verification_id' => $otp->id], 'Otp sent successfully');
        } catch (Exception $e) {
            return $this->fatalErrorResponse($e);
        }
    }

    /**
     * @param User|Authenticatable $user
     * @param bool $event
     * @return mixed
     */
    public function sendOtp(User|Authenticatable $user, bool $event): mixed
    {
        return app(OtpVerificationService::class)->sendOtp(
            User::class,
            $user,
            OtpVerification::PURPOSE_EMAIL_VERIFICATION,
            $user['email'],
            'mail',
            $event
        );
    }

    /**
     * @param CustomerVerifyEmailRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function verifyEmail(CustomerVerifyEmailRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
                app(OtpVerificationService::class)->verifyOtp(
                    User::class,
                    $request->user(),
                    $request->input('code'),
                    OtpVerification::PURPOSE_EMAIL_VERIFICATION,
                    $request->user()['email'],
                    'mail',
                    $request->input('otp_verification_id'),
                );
                $request->user()['email_verified_at'] = now();
                $request->user()['status'] =  Status::VERIFIED->value;
                $request->user()->save();
                ActivityLog::log(
                    User::class,
                    $request->user()->id,
                    $request->user(),
                    ActivityType::USER_VERIFY_EMAIL->value,
                    'user email verified',
                );
            DB::commit();
            return $this->success('Email verified successfully');
        } catch (Exception $e) {
            DB::rollback();
            return $this->fatalErrorResponse($e);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();
        $request->user()->logged_out_at = now();
        $request->user()->save();
        return $this->success('Successfully logged out');
    }
}
