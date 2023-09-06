<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ClientContoller;

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

Route::middleware('authToken')->get('/book-list', [BookController::class, 'getBookList']);

Route::middleware('authToken')->get('/book', [BookController::class, 'getBookDetailsById']);

Route::middleware('authToken')->get('/profile', [ClientContoller::class, 'getProfileInformations']);

Route::middleware('authToken')->post('/update-profile', [ClientContoller::class, 'updateProfileInformations']);

Route::middleware('authToken')->post('/add-bookmark', [BookController::class, 'createBookMark']);