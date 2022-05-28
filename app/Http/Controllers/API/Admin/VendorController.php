<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Vendor\VendorResource;
use App\Models\Vendor\Vendor;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class VendorController extends Controller
{
    /**
     * @return JsonResponse|BinaryFileResponse
     * @throws Exception
     */
    public function index(): BinaryFileResponse|JsonResponse
    {
        return $this->datatableResponse(
            Vendor::with('restaurant')->latest(),
            VendorResource::class
        );
    }
}
