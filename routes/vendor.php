<?php

use App\Http\Controllers\API\Vendor\{CustomNotificationController,
    OrderController,
    RestaurantController,
    ReservationController,
    AuthController,
    MenuController,
    PasswordController,
    ProfileController,
    RoleController,
    TransactionController,
    VendorController,
    WalletController};
use App\Http\Middleware\Vendor\ApprovedVendorMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Vendor API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for vendors
|
*/

Route::get('/', function () {
    return [
        'message' => 'Bridge Order API Server --- Vendor',
    ];
});

Route::prefix('auth')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::middleware(['auth:vendor', 'scope:vendor-access'])->group(function () {
            Route::get('session', 'session');
            Route::post('logout', 'logout');
        });
    });

    Route::controller(PasswordController::class)->prefix('password')->group(function () {
        Route::post('request-otp', 'requestPasswordOtp');
        Route::post('reset', 'resetPassword');
        Route::post('change', 'changePassword')
            ->middleware(['auth:vendor', 'scope:vendor-access']);
    });
});

Route::middleware(['auth:vendor', 'scope:vendor-access', ApprovedVendorMiddleware::class])->group(function () {
    Route::controller(VendorController::class)->prefix('accounts')->group(function () {
        Route::get('', 'index');
        Route::post('add', 'store');
        Route::delete('delete/{vendor}', 'destroy');
    });

    Route::get('roles', [RoleController::class, 'index']);

    Route::controller(ProfileController::class)->prefix('profile')->group(function () {
        Route::patch('update', 'updateProfileDetails');
        Route::patch('update-bank-details', 'updateBankAccount');
    });

    Route::controller(MenuController::class)->prefix('menu')->group(function () {
        Route::get('', 'index');
        Route::post('add', 'store');
        Route::patch('update', 'update');
        Route::delete('delete/{menu}', 'delete');
    });

    Route::prefix('restaurant')->group(function () {
        Route::controller(RestaurantController::class)->group(function () {
            Route::patch('profile/update', 'updateDetails');
        });

        Route::controller(WalletController::class)->prefix('wallet')->group(function () {
            Route::get('', 'index');
            Route::get('requests', 'withdrawalRequests');
            Route::post('request-otp', 'withdrawalRequestOtp');
            Route::post('request-withdrawal', 'requestWithdrawal');
        });

        Route::get('transactions', [TransactionController::class, 'index']);

        Route::controller(ReservationController::class)->prefix('reservations')->group(function () {
            Route::get('', 'index');
        });

        Route::controller(OrderController::class)->prefix('orders')->group(function () {
            Route::get('', 'index');
            Route::patch('{order}/status', 'status');
        });

        Route::controller(CustomNotificationController::class)->prefix('notifications')->group(function () {
            Route::get('', 'index');
            Route::get('unread', 'unread');
            Route::get('read', 'read');
            Route::post('{custom_notification}/view', 'view');
        });
    });
});
