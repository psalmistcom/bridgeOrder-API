<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\Menu\CreateMenuRequest;
use App\Http\Requests\Vendor\Menu\DeleteMenuRequest;
use App\Http\Requests\Vendor\Menu\UpdateMenuRequest;
use App\Http\Resources\Vendor\MenuResource;
use App\Models\Vendor\Menu;
use App\Models\Vendor\Variant;
use App\Services\Media\Cloudinary;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class MenuController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse|BinaryFileResponse
     * @throws Exception
     */
    public function index(Request $request): BinaryFileResponse|JsonResponse
    {
        return $this->datatableResponse(
            Menu::with('category', 'variants', 'vendor')
                ->whereRestaurantId($request->user()->restaurant_id)
                ->latest(),
            MenuResource::class
        );
    }

    /**
     * @param CreateMenuRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(CreateMenuRequest $request): JsonResponse
    {
        try {
            $cloudinary = new Cloudinary();
            $variantImage = null;
            $variantRequest = null;

            if ($request->has('variant')) {
                $variantRequest = $request->input('variant');
                $count = count($request->input('variant'));
                for ($i = 0; $i < $count; $i++) {
                    if ($request->file('variant')[$i]['image']) {
                        $imageName = $request->file('variant')[$i]['image']->getRealPath();
                        $variantImage = $cloudinary->uploadFile($imageName);
                    }
                    $variantRequest[$i]['image'] = $variantImage[0] ?? null;
                }
            }

            if ($request->hasFile('image')) {
                $menuImage = $cloudinary->uploadFile($request->file('image')->getRealPath());
            }

            DB::beginTransaction();
            if (!$request->user()->restaurant->categories->where('id', $request->input('category_id'))->first()) {
                DB::table('category_restaurant')->insert([
                    'category_id' => $request->input('category_id'),
                    'restaurant_id' => $request->user()->restaurant->id
                ]);
            }
                $menu = Menu::create([
                    'category_id' => $request->category_id,
                    'vendor_id' => $request->user()->id,
                    'restaurant_id' => $request->user()->restaurant->id,
                    'item_name' => $request->input('item_name'),
                    'price' => $request->input('price'),
                    'image_public_id' => $menuImage[1] ?? null,
                    'image' => $menuImage[0] ?? null
                ]);
            if ($request->has('variant')) {
                foreach ($variantRequest as $variant) {
                    $menu->variants()->create([
                        'item_name' => $variant['item_name'],
                        'price' => $variant['price'],
                        'image_public_id' => $variant[1] ?? null,
                        'image' => $variant['image']
                    ]);
                }
            }
            DB::commit();
            return $this->successResponse($menu);
        } catch (Exception $e) {
            DB::rollback();
            return $this->fatalErrorResponse($e);
        }
    }

    /**
     * @param UpdateMenuRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(UpdateMenuRequest $request): JsonResponse
    {
        try {
            $count = null;
            $menu = Menu::find($request->input('menu_id'));
            if ($request->input('variant')) {
                $count = count($request->input('variant'));
            }
            $variantIdArray = [];
            $variantImage = null;
            $cloudinary = new Cloudinary();

            DB::beginTransaction();
            if ($request->hasFile('image')) {
                $menuImage = $cloudinary->uploadFile($request->file('image')->getRealPath());
            }

            $menu->update([
                'category_id' => $request->input('category_id') ?? $menu->category_id,
                'vendor_id' => $request->user()->id,
                'restaurant_id' => $request->user()->restaurant->id,
                'in_stock' => $request->input('in_stock') ?? $menu->in_stock,
                'item_name' => $request->input('item_name') ?? $menu->item_name,
                'price' => $request->input('price') ?? $menu->price,
                'image_public_id' => $menuImage[1] ?? $menu['image_public_id'],
                'image' => $menuImage[0] ?? $menu['image_public_id']
            ]);

            if ($request->input('variant')) {
                for ($i = 0; $i < $count; $i++) {
                    $variantId = $request->input('variant')[$i]['id'] ?? null;
                    $variantIdArray[] = $variantId;

                    if ($variantId) {
                        // check existence of variant and update it
                        $variant = Variant::whereId($variantId)
                            ->whereMenuId($menu->id)->first();

                        $variantImageFile = $request->file('variant')[$i]['image'] ?? null;
                        if ($variantImageFile) {
                            $imageName = $request->file('variant')[$i]['image']->getRealPath();
                            $variantImage = $cloudinary->uploadFile($imageName);
                        }

                        $variant->update([
                            'item_name' => $request->input('variant')[$i]['item_name'] ?? $variant['item_name'],
                            'price' => $request->input('variant')[$i]['price'] ?? $variant['price'],
                            'image_public_id' => $variantImage[1] ?? $variant['image_public_id'],
                            'image' => $variantImage['image'] ?? $variant['image']
                        ]);
                    }

                    if (!$variantId) {
                        // the variant is new and needs to be inputted into the database
                        if ($request->file('variant')[$i]['image']) {
                            $imageName = $request->file('variant')[$i]['image']->getRealPath();
                            $variantImage = $cloudinary->uploadFile($imageName);
                        }

                        $menu->variants()->create([
                            'item_name' => $request->input('variant')[$i]['item_name'],
                            'price' => $request->input('variant')[$i]['price'],
                            'image_public_id' => $variantImage[1] ?? null,
                            'image' => $variantImage[0]
                        ]);
                    }
                }
                // delete variant whose id was not included in the request
                $variantDiff = array_diff($menu->variants->pluck('id')->toArray(), $variantIdArray);
                if (count($menu->variants->whereIn('id', $variantDiff)) > 0) {
                    $menu->variants->whereIn('id', $variantDiff)->first()->delete();
                }
            }

            DB::commit();
            $menu->refresh();
            return $this->successResponse($menu);
        } catch (Exception $e) {
            DB::rollback();
            return $this->fatalErrorResponse($e);
        }
    }

    /**
     * @param Menu $menu
     * @return JsonResponse
     */
    public function delete(Menu $menu): JsonResponse
    {
        try {
            $menu->variants()->delete();
            $menu->favourites()->delete();
            $menu->delete();
            return $this->success('Menu deleted successfully.');
        } catch (Exception $e) {
            return $this->fatalErrorResponse($e);
        }
    }
}
