<?php

use App\Http\Controllers\API\Admin\AuthController;
use App\Http\Controllers\API\Admin\CustomerController;
use App\Http\Controllers\API\Admin\PasswordController;
use App\Http\Controllers\API\Admin\RestaurantController;
use App\Http\Controllers\API\Admin\RoleController;
use App\Http\Controllers\API\Admin\TransactionController;
use App\Http\Controllers\API\Admin\VendorController;
use App\Http\Controllers\API\Admin\WalletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for admin users
|
*/

Route::get('/', function () {
    return [
        'message' => 'Bridge Order API Server --- Admin',
    ];
});

Route::prefix('auth')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::middleware(['auth:admin', 'scope:admin-access'])->group(function () {
            Route::post('register', 'register');
            Route::get('session', 'session');
            Route::post('logout', 'logout');
        });
    });
    Route::controller(PasswordController::class)->prefix('password')->group(function () {
        Route::post('request-otp', 'requestPasswordOtp');
        Route::post('reset', 'resetPassword');
        Route::post('change', 'changePassword')
            ->middleware(['auth:admin', 'scope:admin-access']);
    });
});

Route::middleware(['auth:admin', 'scope:admin-access'])->group(function () {
    Route::controller(CustomerController::class)->prefix('customers')->group(function () {
        Route::get('', 'index');
        Route::patch('update-status/{user}', 'updateStatus');
        Route::delete('delete/{user}', 'destroy');
    });

    Route::controller(RestaurantController::class)->prefix('restaurants')->group(function () {
        Route::get('', 'index');
        Route::get('categories', 'category');
        Route::post('category/add', 'addCategory');
        Route::patch('update-status/{restaurant}', 'updateStatus');
        Route::delete('delete/{restaurant}', 'destroy');
    });

    Route::controller(VendorController::class)->prefix('vendors')->group(function () {
        Route::get('', 'index');
    });

    Route::get('roles', [RoleController::class, 'index']);

    Route::controller(WalletController::class)->prefix('vendor/wallet')->group(function () {
        Route::get('requests', 'withdrawalRequests');
        Route::post('requests/process', 'processRequest');
    });

    Route::controller(TransactionController::class)->prefix('vendor/transactions')->group(function () {
        Route::get('', 'index');
    });

//    Route::controller(CustomNotificationController::class)->prefix('notifications')->group(function () {
//        Route::get('', 'index');
//        Route::get('unread', 'unread');
//        Route::get('read', 'read');
//        Route::post('{custom_notification}/view', 'view');
//    });
});
