<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Admin\CarController;
use App\Http\Controllers\Api\Admin\TransactionController;
use App\Http\Controllers\Api\User\CarController as UserCarController;
use App\Http\Controllers\Api\User\RentCarController;
use App\Http\Controllers\Api\User\MyOrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::prefix('admin')->group(function () {
        Route::resource('car', CarController::class);
        Route::resource('transaction', TransactionController::class);
    });

    Route::get('my-order', [MyOrderController::class, 'index']);
    Route::post('process-payment', [MyOrderController::class, 'processPaymentRent']);
    Route::post('rent-car/{car:slug}', [RentCarController::class, 'store']);

    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('car', [UserCarController::class, 'index']);
Route::get('car/{car:slug}', [UserCarController::class, 'show']);
