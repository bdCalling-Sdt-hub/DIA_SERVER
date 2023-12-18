<?php

use App\Http\Controllers\Api\quizeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ===================== AUTHENTIKETION ====================== //

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
// Route::get('/verification/{id}', [UserController::class, 'verification']);
Route::post('/verified', [UserController::class, 'verifiedOtp']);
Route::post('/getOtp', [UserController::class, 'sendOtp']);
Route::group(['middleware' => 'api'], function ($routes) {
    Route::get('/profile', [UserController::class, 'profile']);
    Route::get('/edit/profile/{id}', [UserController::class, 'profileEdit']);
    Route::get('/refresh-token', [UserController::class, 'refreshToken']);
    Route::get('/logout', [UserController::class, 'logout']);
    Route::post('/reset-password', [UserController::class, 'resetPassword']);
    Route::post('/profileUpdate', [UserController::class, 'profileUpdate']);

    // ====================CATEGORY =================//

    Route::post('/category', [quizeController::class, 'Category']);
    Route::get('/edit/category/{id}', [quizeController::class, 'editCategory']);
    Route::post('/update/category', [quizeController::class, 'updateCategory']);
    Route::post('/update/category/image', [quizeController::class, 'updateCatImg']);
    Route::post('/delete/category/image', [quizeController::class, 'deleteCatImg']);
    Route::get('/delete/category/{id}', [quizeController::class, 'deleteCategory']);
    Route::get('/all/category', [quizeController::class, 'getCategory']);

    // ======================SUBCATEGORY===================//

    Route::get('/all/sub/category', [quizeController::class, 'getSubCategory']);
    Route::post('/sub/category', [quizeController::class, 'subCategory']);
    Route::get('/edit/sub/category/{id}', [quizeController::class, 'editSubCategory']);
    Route::post('/update/sub/category', [quizeController::class, 'updateSubCategory']);
    Route::post('/update/sub/category/image', [quizeController::class, 'updateSubCatImg']);
    Route::post('/delet/sub/category/image', [quizeController::class, 'deleteSubCatImg']);
    Route::get('/delete/sub/category/{id}', [quizeController::class, 'deleteSubCategory']);

    // CATEGORY SUB CATEGORY //

    Route::get('/dependncy/category', [quizeController::class, 'categorySubCategory']);

    // ================= DISPLAY QUIZE ===================//

    Route::get('/show/question/{id}', [quizeController::class, 'displayQuestion']);

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

    // =================== STORY CREATE ===========================//

    Route::post('/story', [quizeController::class, 'story']);
    Route::get('/edit/story/{id}', [quizeController::class, 'editStory']);
    Route::get('/get/story', [quizeController::class, 'getStory']);
    Route::post('/update/story', [quizeController::class, 'UpdateStory']);
    Route::get('/delete/story/{id}', [quizeController::class, 'removeStory']);
    Route::post('/update/story/img', [quizeController::class, 'updateStoryImg']);
    Route::post('/delete/story/img', [quizeController::class, 'deleteStoryImg']);

    // =================== Answere submitted ===========================//

    Route::post('/anser/submit', [quizeController::class, 'answare']);

    // =================== Like ===========================//
    Route::post('/like', [quizeController::class, 'Like']);

    // =================== Comments ===========================//
    Route::post('/comments', [quizeController::class, 'comments']);
    Route::get('/delete/comments/{id}', [quizeController::class, 'DeleteComments']);
});
