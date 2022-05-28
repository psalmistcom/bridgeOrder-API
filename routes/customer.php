<?php

use App\Http\Controllers\API\Customer\{AuthController,
    OrderController,
    PaymentCardController,
    PasswordController,
    ReservationController,
    TransactionController,
    WalletController};
use App\Http\Controllers\API\FavouriteController;
use App\Http\Middleware\Customer\VerifiedCustomerMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Customer API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for customers
|
*/

Route::get('/', function () {
    return [
        'message' => 'Bridge Order API Server --- Customer',
    ];
});

Route::prefix('auth')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::middleware(['auth:api', 'scope:customer-access'])->group(function () {
            Route::post('request-otp', 'resendOtp');
            Route::post('verify-email', 'verifyEmail');
            Route::get('session', 'session');
            Route::post('logout', 'logout');
        });
    });

    Route::controller(PasswordController::class)->prefix('password')->group(function () {
        Route::post('request-otp', 'requestPasswordOtp');
        Route::post('reset', 'resetPassword');
        Route::post('change', 'changePassword')
            ->middleware(['auth:api', 'scope:customer-access']);
    });
});

Route::middleware(['auth:api', 'scope:customer-access', VerifiedCustomerMiddleware::class])->group(function () {
    Route::controller(PaymentCardController::class)->prefix('card')->group(function () {
        Route::get('', 'index');
        Route::post('add', 'addCard');
        Route::post('topup-wallet', 'topUpWalletWithCard');
        Route::patch('make-active', 'makeCardActive');
        Route::delete('delete', 'destroy');
    });

    Route::get('wallet', [WalletController::class, 'index']);

    Route::controller(TransactionController::class)->prefix('transactions')->group(function () {
        Route::get('', 'index');
        Route::get('wallet', 'wallet');
    });

    Route::controller(ReservationController::class)->prefix('reservations')->group(function () {
        Route::get('', 'index');
        Route::post('make-reservation', 'makeReservation');
    });

    Route::controller(FavouriteController::class)->prefix('favourites')->group(function () {
        Route::get('', 'index');
        Route::post('toggle', 'toggleFavourite');
    });

    Route::controller(OrderController::class)->prefix('orders')->group(function () {
        Route::get('', 'index');
        Route::post('place', 'placeOrder');
    });

//    Route::controller(CustomNotificationController::class)->prefix('notifications')->group(function () {
//        Route::get('', 'index');
//        Route::get('unread', 'unread');
//        Route::get('read', 'read');
//        Route::post('{custom_notification}/view', 'view');
//    });
});
