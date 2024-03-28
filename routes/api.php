<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WishListController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); 

//User Controller  
Route::post('register',[UserController::class,'register']);
Route::put('/upload-avatar/{id}', [UserController::class,'uploadAvatar']);
Route::get('user/{id}',[UserController::class,'getUser']);
Route::post('login',[UserController::class,'login']);
Route::get('usersearch/{key}', [UserController::class, 'searchUser']);
Route::post('/checkEmail', [UserController::class, 'checkEmail']);
Route::post('/checkUsername', [UserController::class, 'checkUsername']);
Route::put('user/{id}', [UserController::class, 'update']);
Route::put('user/{id}/update-password', [UserController::class, 'updatePassword']);
Route::post('user/{id}/check-old-password', [UserController::class, 'checkOldPassword']);

//Product Controller
Route::post('addproduct',[ProductController::class,'addProduct']);
Route::get('list',[ProductController::class,'list']);
Route::get('user/{uid}/products', [ProductController::class, 'getUserProducts']);
Route::delete('delete/{pid}',[ProductController::class,'deleteProduct']);
Route::get('newest-products', [ProductController::class, 'getNewestProducts']);
Route::get('most-expensive-products', [ProductController::class, 'getMostExpensiveProducts']);
Route::put('update/{pid}',[ProductController::class,'updateProduct']);
Route::put('auction/{pid}',[ProductController::class,'updatePrice']);
Route::get('search/{key}', [ProductController::class, 'search']);
Route::get('product/{pid}',[ProductController::class,'getProduct']);
Route::get('product/{pid}/remaining-time', [ProductController::class, 'getRemainingTime']);
Route::get('/top-auctions', [ProductController::class, 'getTopAuctions']);

// wishlist Controller
Route::post('wish',[WishListController::class,'addToWishList']);
Route::get('getproducts/{uid}',[WishListController::class,'getWishList']);
Route::delete('remove-from-wishlist/{uid}/{pid}', [WishListController::class,'removeFromWishList']);