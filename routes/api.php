<?php

use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Client\ProductsController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\OrdersController as OrdersControllerClient;
use App\Http\Controllers\Admin\ProductsController as ProductsControllerAdmin;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/refresh-token', [UsersController::class, 'refreshToken'])->name('user.refresh-token');
Route::post('/login', [UsersController::class, 'login'])->name('user.login');
Route::post('/createUser', [UsersController::class, 'createUser'])->name('user.createUser');

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [UsersController::class, 'logout'])->name('user.logout');
    Route::get('/store', [ProductsController::class, 'store'])->name('client.store');
    Route::get('/show/{id}', [ProductsController::class, 'show'])->name('client.show');
    Route::get('/orders/show', [OrdersControllerClient::class, 'showOrders'])->name('client.showOrders');
    Route::post('/order/create', [OrdersControllerClient::class, 'createOrder'])->name('client.createOrder');
    Route::post('/order/cancellation', [OrdersControllerClient::class, 'cancellationOfTheOrder'])->name('client.cancellationOrder');

    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/orders', [OrdersController::class, 'getOrders'])->name('admin.orders');
        Route::get('/order/{id}', [OrdersController::class, 'getOrder'])->name('admin.order');
        Route::get('/orderNew', [OrdersController::class, 'newOrder'])->name('admin.order.new'); // Залишаємо тільки цей маршрут
        Route::post('/order/create', [OrdersController::class, 'createOrder'])->name('admin.createOrder');
        Route::post('/order/update', [OrdersController::class, 'updateOrder'])->name('admin.updateOrder');
        Route::delete('/order/delete', [OrdersController::class, 'deleteOrder'])->name('admin.deleteOrder');


        Route::post('/productCreate', [ProductsControllerAdmin::class, 'create'])->name('admin.product.create');
        Route::get('/store', [ProductsController::class, 'store'])->name('admin.store');
        Route::delete('/product/{id}', [ProductsControllerAdmin::class, 'delete'])->name('admin.delete');
        Route::get('/show/{id}', [ProductsControllerAdmin::class, 'show'])->name('admin.show');
        Route::post('/product/update', [ProductsControllerAdmin::class, 'update'])->name('admin.product.update');
        Route::delete('/product/delete', [ProductsControllerAdmin::class, 'deleteOrder'])->name('admin.delete.product');

        Route::get('/users', [UsersController::class, 'getUsers'])->name('admin.users');
        Route::get('/user/{id}', [UsersController::class, 'getUser'])->name('admin.user');
        Route::post('/user/update', [UsersController::class, 'updateUser'])->name('admin.updateUser');
        Route::delete('/user/delete', [UsersController::class, 'deleteUser'])->name('admin.deleteUser');
        Route::post('/user/create', [UsersController::class, 'createUserForAdmin'])->name('admin.createUserForAdmin');
    });
});

