<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Restaurant\DeleteRestaurantRequest;
use App\Http\Requests\Admin\Restaurant\UpdateRestaurantStatusRequest;
use App\Http\Resources\Vendor\CategoryResource;
use App\Http\Resources\Vendor\RestaurantResource;
use App\Models\Vendor\Category;
use App\Models\Vendor\Restaurant;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RestaurantController extends Controller
{
    /**
     * @return JsonResponse|BinaryFileResponse
     * @throws Exception
     */
    public function index(): BinaryFileResponse|JsonResponse
    {
        return $this->datatableResponse(
            Restaurant::with('wallet')->latest(),
            RestaurantResource::class
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function addCategory(Request $request): JsonResponse
    {
        try {
            $category = Category::create([
                'name' => $request->input('name'),
                'slug' => Str::slug($request->input('name'), '_')
            ]);
            return $this->successResponse($category);
        } catch (Exception $e) {
            return $this->fatalErrorResponse($e);
        }
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function category(): JsonResponse
    {
        return $this->datatableResponse(
            Category::latest(),
            CategoryResource::class
        );
    }

    /**
     * @param Restaurant $restaurant
     * @param UpdateRestaurantStatusRequest $request
     * @return JsonResponse
     */
    public function updateStatus(Restaurant $restaurant, UpdateRestaurantStatusRequest $request): JsonResponse
    {
        try {
            $restaurant->update([
                'status' => $request->input('status')
            ]);

            return $this->success('Restaurant status updated successfully');
        } catch (Exception $e) {
            return $this->fatalErrorResponse($e);
        }
    }

    /**
     * @param Restaurant $restaurant
     * @return JsonResponse
     */
    public function destroy(Restaurant $restaurant): JsonResponse
    {
        try {
            $restaurant->delete();
            return $this->success('Restaurant deleted successfully');
        } catch (Exception $e) {
            return $this->fatalErrorResponse($e);
        }
    }
}
