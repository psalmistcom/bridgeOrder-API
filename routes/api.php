<?php

use App\Http\Controllers\API\BankController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CustomNotificationController;
use App\Http\Controllers\API\RestaurantController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for the general use of the app
|
*/

Route::get('/', function () {
    return [
        'message' => 'Bridge Order API Server',
    ];
});

Route::controller(RestaurantController::class)->prefix('restaurant')->group(function () {
    Route::get('all', 'allRestaurants');
    Route::get('menu/{restaurant}', 'menuByRestaurant');
    Route::get('menu/{restaurant}/{category}', 'menuByRestaurantCategory');
    Route::get('{restaurant}/categories', 'category');
    Route::get('{restaurant}', 'restaurant');
});

Route::get('categories', [CategoryController::class, 'index']);
Route::get('banks', [BankController::class, 'index']);
