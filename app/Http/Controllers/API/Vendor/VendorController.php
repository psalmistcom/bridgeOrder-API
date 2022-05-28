<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\Account\CreateVendorAccountRequest;
use App\Http\Requests\Vendor\Account\DeleteVendorAccountRequest;
use App\Http\Resources\Vendor\VendorAccountResource;
use App\Models\Vendor\Vendor;
use App\Notifications\Vendor\NewVendorNotification;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class VendorController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse|BinaryFileResponse
     * @throws Exception
     */
    public function index(Request $request): BinaryFileResponse|JsonResponse
    {
        return $this->datatableResponse(
            Vendor::whereRestaurantId($request->user()->id)
                ->with('role', 'restaurant')
                ->latest(),
            VendorAccountResource::class
        );
    }

    /**
     * @param CreateVendorAccountRequest $request
     * @return JsonResponse
     */
    public function store(CreateVendorAccountRequest $request): JsonResponse
    {
        try {
            $password = generateRandomString(7);
            $vendor = Vendor::create([
                'full_name' => $request->input('full_name'),
                'email' => $request->input('email'),
                'password' => Hash::make($password),
                'restaurant_id' => $request->user()->restaurant->id,
                'role_id' => $request->input('role_id')
            ]);
            $vendor->notify(new NewVendorNotification($password, $request->user()->restaurant));
            return $this->successResponse($vendor, 'Vendor added successfully');
        } catch (Exception $e) {
            return $this->fatalErrorResponse($e);
        }
    }

    /**
     * @param Vendor $vendor
     * @return JsonResponse
     */
    public function destroy(Vendor $vendor): JsonResponse
    {
        try {
            $vendor->delete();
            return $this->success('Vendor deleted successfully');
        } catch (Exception $e) {
            return $this->fatalErrorResponse($e);
        }
    }
}
