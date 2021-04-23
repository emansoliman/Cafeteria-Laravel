<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource("/products",ProductController::class);

Route::apiResource("/orders",OrderController::class);
Route::get("/orders/price/{order}", [OrderController::class,'orderPrice']);
Route::post("/orders/search-users", [OrderController::class,'searchOrderUsersByDate']);
Route::get("/orders/search-orders/{user_id}", [OrderController::class,'searchOrdersByDate']);
Route::get("/orders/{order_id}/products", [OrderController::class,'getOrderProducts']);

Route::post('/register', [App\Http\Controllers\RegisterController::class,'register']);
Route::get('/users/{user}', [App\Http\Controllers\UserController::class,'userOrdersPrice']);
Route::get('/users', [App\Http\Controllers\UserController::class,'index']);
Route::apiResource('/rooms', App\Http\Controllers\RoomController::class);
Route::post('upload', [App\Http\Controllers\ImageController::class,'upload']);
Route::post('/login', [App\Http\Controllers\LoginController::class,'login']);
Route::post('/logout', [App\Http\Controllers\LoginController::class,'logout']);
