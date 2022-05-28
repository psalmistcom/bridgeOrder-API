<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\Profile\UpdateBankRequest;
use App\Http\Requests\Vendor\Profile\UpdateProfileRequest;
use App\Services\Finance\Paystack;
use App\Services\Media\Cloudinary;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProfileController extends Controller
{
    /**
     * @param UpdateProfileRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function updateProfileDetails(UpdateProfileRequest $request): JsonResponse
    {
        $cloudinary = new Cloudinary();
        try {
            DB::beginTransaction();
            if ($request->hasFile('image')) {
                $image = $cloudinary->uploadFile(
                    $request->file('image')->getRealPath(),
                    $request->user()
                );
            }

                $request->user()->update([
                    'full_name' => $request->input('full_name'),
                    'email' => $request->input('email'),
                    'image_public_id' => $image[1] ?? $request->user()->image_public_id,
                    'image' => $image[0] ?? $request->user()->image
                ]);

            DB::commit();
            return $this->success('Profile updated successfully');
        } catch (Exception $e) {
            DB::rollback();
            return $this->fatalErrorResponse($e);
        }
    }

    public function updateBankAccount(UpdateBankRequest $request): JsonResponse
    {
        try {
            $resolveAccount = app(Paystack::class)->resolveAccountNumber(
                $request->input('account_number'),
                $request->input('bank_code')
            );

            $request->user()->restaurant->update([
                'account_name' => $resolveAccount['data']['account_name'],
                'account_number' => $resolveAccount['data']['account_number'],
                'bank_code' => $request->input('bank_code')
            ]);
            return $this->successResponse(
                $request->user()->restaurant,
                'Restaurant bank details updated successfully.'
            );
        } catch (Exception $e) {
            return $this->fatalErrorResponse($e);
        }
    }
}
