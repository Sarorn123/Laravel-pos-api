<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Sell\SellController;

Route::group(['middleware' => ['apiKey']], function () {

    Route::post('user/register', [AuthController::class, 'register']);
    Route::post('user/login', [AuthController::class, 'login']);

    Route::group(['middleware' => 'auth:sanctum'], function () {

        // End Point For Admin
        Route::group(['middleware' => ['admin']], function () {

            // Product //
            Route::get('product/getProduct/{id}', [ProductController::class, 'getProduct']);
            Route::post('product/addEditProduct', [ProductController::class, 'addEditProduct']);
            Route::delete('product/deleteProduct/{id}', [ProductController::class, 'deleteProduct']);
            Route::get('product/getAllproducts', [ProductController::class, 'getAllProducts']);

            // Employee //
            Route::apiResource('/employee', EmployeeController::class);

            // Customer //
            Route::apiResource('/customer', CustomerController::class);
            
            // Sell //
            Route::apiResource('/sell', SellController::class);
            Route::get('/getSellFormData', [SellController::class, 'getSellFormData']);
            Route::get('/getAnalyticeToday', [SellController::class, 'getAnalyticeToday']);
            Route::get('/getDashboardData', [DashboardController::class, 'getDashboardData']);

        });

    });
});
