<?php

use App\Http\Controllers\Api\quizeController;
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
Route::post('/update/question/image/two', [quizeController::class, 'updateQuestionImg2']);
Route::get('/delete/question/{id}', [quizeController::class, 'removeQuestion']);

// ================= DISPLAY QUIZE ===================//


