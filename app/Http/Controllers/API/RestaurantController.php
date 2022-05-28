<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\RestaurantResource;
use App\Models\Vendor\Category;
use App\Models\Vendor\Menu;
use App\Models\Vendor\Restaurant;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * @param Restaurant $restaurant
     * @return JsonResponse
     */
    public function category(Restaurant $restaurant): JsonResponse
    {
        return $this->successResponse($restaurant->categories);
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function allRestaurants(): JsonResponse
    {
        return $this->successResponse(
            RestaurantResource::collection(Restaurant::with('categories')->get())
        );
    }

    /**
     * @param Restaurant $restaurant
     * @return JsonResponse
     */
    public function restaurant(Restaurant $restaurant): JsonResponse
    {
        return $this->successResponse($restaurant);
    }

    /**
     * @param Restaurant $restaurant
     * @param Category $category
     * @return JsonResponse
     */
    public function menuByRestaurantCategory(Restaurant $restaurant, Category $category): JsonResponse
    {
        return $this->successResponse(
            Menu::whereCategoryId($category['id'])->whereRestaurantId($restaurant['id'])->get()
        );
    }

    /**
     * @param Restaurant $restaurant
     * @return JsonResponse
     */
    public function menuByRestaurant(Restaurant $restaurant): JsonResponse
    {
        return $this->successResponse($restaurant->menus);
    }
}
