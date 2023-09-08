<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ClientContoller;
use App\Http\Controllers\FeedbackController;
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

Route::post('register', [AuthController::class, 'registerNewUser']);

Route::post('login', [AuthController::class, 'loginUser']);

Route::middleware('authToken')->post('/book-list', [BookController::class, 'getBookList']);

Route::middleware('authToken')->post('/book', [BookController::class, 'getBookDetailsById']);

Route::middleware('authToken')->get('/profile', [ClientContoller::class, 'getProfileInformations']);

Route::middleware('authToken')->post('/update-profile', [ClientContoller::class, 'updateProfileInformations']);

Route::middleware('authToken')->post('/add-bookmark', [BookController::class, 'createBookMark']);

Route::middleware('authToken')->get('/bookmark-list', [BookController::class, 'getBookMarkListByUser']);

Route::middleware('authToken')->post('/create-feedback', [FeedbackController::class, 'addNewClientReview']);

Route::middleware('authToken')->post('/addToCart', [CartController::class, 'addItemsToCart']);

Route::middleware('authToken')->get('/allCartItems', [CartController::class, 'getAllCartItems']);

Route::middleware('authToken')->post('/placeOrder', [OrderController::class, 'placeNewOrder']);