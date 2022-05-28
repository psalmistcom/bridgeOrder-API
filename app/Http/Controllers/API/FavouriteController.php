<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Favourite\CreateFavouriteRequest;
use App\Http\Resources\FavouriteResource;
use App\Models\Favourite;
use App\Models\Vendor\Menu;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class FavouriteController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return $this->successResponse(
            FavouriteResource::collection(
                Favourite::whereUserId($request->user()->id)->get()
            )
        );
    }

    /**
     * @param CreateFavouriteRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function toggleFavourite(CreateFavouriteRequest $request): JsonResponse
    {
        try {
            $menu = Menu::find($request->input('menu_id'));
            return match (true) {
                Favourite::whereUserId($request->user()->id)->whereMenuId($menu->id)->exists()
                => $this->deleteFavourite($menu->id, $request->user()->id),
                default => $this->addFavourite($menu, $request->user()->id),
            };
        } catch (Exception $e) {
            return $this->fatalErrorResponse($e);
        }
    }

    /**
     * @param int $menuId
     * @param int $userId
     * @return JsonResponse
     */
    private function deleteFavourite(int $menuId, int $userId): JsonResponse
    {
        Favourite::whereUserId($userId)->whereMenuId($menuId)->delete();

        return $this->success('Menu removed from favourites list successfully.');
    }

    /**
     * @param Menu $menu
     * @param int $userId
     * @return JsonResponse
     */
    private function addFavourite(Menu $menu, int $userId): JsonResponse
    {
        Favourite::create([
            'restaurant_id' => $menu->restaurant->id,
            'menu_id' => $menu->id,
            'user_id' => $userId,
        ]);

        return $this->success('Menu added to favourites list successfully.');
    }
}
