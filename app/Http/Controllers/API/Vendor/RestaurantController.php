<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\Restaurant\UpdateRestaurantRequest;
use App\Services\Media\Cloudinary;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class RestaurantController extends Controller
{
    /**
     * @param UpdateRestaurantRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function updateDetails(UpdateRestaurantRequest $request): JsonResponse
    {
        $cloudinary = new Cloudinary();
        try {
            DB::beginTransaction();
            if ($request->hasFile('image')) {
                $image = $cloudinary->uploadFile($request->file('image')->getRealPath(), $request->user()->restaurant);
            }

                $request->user()->restaurant->update([
                    'name' => $request->input('name'),
                    'allow_reservation' => $request->input('allow_reservation'),
                    'reservation_price' => $request->input('reservation_price'),
                    'image_public_id' => $image[1] ?? $request->user()->restaurant['image_public_id'],
                    'image' => $image[0] ?? $request->user()->restaurant['image']
                ]);

            DB::commit();
            return $this->successResponse(
                $request->user()->restaurant,
                'Restaurant details updated successfully.'
            );
        } catch (Exception $e) {
            DB::rollback();
            return $this->fatalErrorResponse($e);
        }
    }
}
