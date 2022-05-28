<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\Auth\LoginRequest;
use App\Http\Requests\Vendor\Auth\RegisterRequest;
use App\Http\Resources\Vendor\VendorResource;
use App\Models\Role;
use App\Models\Vendor\Category;
use App\Models\Vendor\Restaurant;
use App\Models\Vendor\Vendor;
use App\Notifications\TestNotification;
use App\Notifications\Vendor\RegistrationNotification;
use App\Services\Finance\WalletService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
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
            $vendorRoleId = Role::whereSlug(Vendor::ROLE_VENDOR_ADMIN)->first()->id;
            DB::beginTransaction();
                $restaurant = Restaurant::create([
                    'name' => $request->input('restaurant_name'),
                    'slug' => Str::slug($request->input('restaurant_name'), '_'),
                ]);
                $vendor = Vendor::create([
                    'restaurant_id' => $restaurant->id,
                    'full_name' => $request->input('full_name'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password')),
                    'role_id' => $vendorRoleId

                ]);

                WalletService::createWallet($restaurant);

                $response['token'] = $vendor->createToken('vendor', ['vendor-access'])->accessToken;
                $response['user'] = VendorResource::make($vendor);
                $vendor->logged_in_at = now();
                $vendor->save();
            DB::commit();
            $vendor->notify(new RegistrationNotification($restaurant));
            return $this->successResponse($response, 'Registration successful');
            /*
              * dispatch a queued mail to vendor
              * return all necessary data 'with' vendor
              * */
        } catch (Exception $e) {
            DB::rollback();
            return $this->fatalErrorResponse($e);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $vendor = Vendor::query()->whereEmail($request->input('email'))
            ->with('restaurant', 'restaurant.wallet', 'role')
            ->first();

        if (!$vendor || !Hash::check($request->input('password'), $vendor->password)) {
            return $this->error('Invalid credentials provided');
        }

        $response['token'] =  $vendor->createToken('vendor', ['vendor-access'])->accessToken;
        $response['user'] =  VendorResource::make($vendor);
        $vendor->logged_in_at = now();
        $vendor->save();

        return $this->successResponse($response, 'Login successful');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function session(Request $request): JsonResponse
    {
        $vendor = Vendor::query()->with('restaurant', 'restaurant.wallet', 'role')
            ->find($request->user()->id);

        return response()->json([
            'status' => true,
            'message' => 'Authenticated',
            'user' => VendorResource::make($vendor),
        ]);
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
