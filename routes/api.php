<?php

use App\Http\Controllers\Api\quizeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | API Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register API routes for your application. These
 * | routes are loaded by the RouteServiceProvider and all of them will
 * | be assigned to the "api" middleware group. Make something great!
 * |
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// CATEGORY //

Route::get('/all/category', [quizeController::class, 'getCategory']);
Route::post('/category', [quizeController::class, 'Category']);
Route::get('/edit/category/{id}', [quizeController::class, 'editCategory']);
Route::post('/update/category', [quizeController::class, 'updateCategory']);
Route::get('/delete/category/{id}', [quizeController::class, 'deleteCategory']);

// SUBCATEGORY //

Route::get('/all/sub/category', [quizeController::class, 'getSubCategory']);
Route::post('/sub/category', [quizeController::class, 'subCategory']);
Route::get('/edit/sub/category/{id}', [quizeController::class, 'editSubCategory']);
Route::post('/update/sub/category', [quizeController::class, 'updateSubCategory']);
Route::get('/delete/sub/category/{id}', [quizeController::class, 'deleteSubCategory']);

// CATEGORY SUB CATEGORY //

Route::get('/dependncy/category', [quizeController::class, 'categorySubCategory']);

// QUESTIONS SECTION //

Route::get('/all/question', [quizeController::class, 'getQuestion']);
Route::post('/question', [quizeController::class, 'questions']);
Route::get('/edit/question/{id}', [quizeController::class, 'editQuestion']);
Route::post('/update/question', [quizeController::class, 'updateQuestion']);
Route::post('/update/question/image/one', [quizeController::class, 'updateQuestionImg1']);
Route::post('/delete/question/image/one', [quizeController::class, 'deleteQuestion1Img']);
Route::post('/delete/question/image/two', [quizeController::class, 'deleteQuestion2Img']);
Route::post('/update/question/image/two', [quizeController::class, 'updateQuestionImg2']);
Route::get('/delete/question/{id}', [quizeController::class, 'removeQuestion']);

// ================= DISPLAY QUIZE ===================//

Route::get('/show/question/{id}', [quizeController::class, 'displayQuestion']);

// =================== STORY CREATE ===========================//

Route::post('/story', [quizeController::class, 'story']);

// ===================== AUTHENTIKETION ====================== //

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
// Route::get('/verification/{id}', [UserController::class, 'verification']);
Route::post('/verified', [UserController::class, 'verifiedOtp']);
// Route::get('/resend-otp', [UserController::class, 'resendOtp']);
Route::post('/getOtp', [UserController::class, 'sendOtp']);
Route::group(['middleware' => 'api'], function ($routes) {
    Route::get('/profile', [UserController::class, 'profile']);
    Route::get('/edit/profile/{id}', [UserController::class, 'profileEdit']);
    Route::get('/refresh-token', [UserController::class, 'refreshToken']);
    Route::get('/logout', [UserController::class, 'logout']);
    Route::post('/reset-password', [UserController::class, 'resetPassword']);
    Route::post('/profileUpdate', [UserController::class, 'profileUpdate']);
});
