<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApiController;

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

// Auth routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'authenticate']);
// Api routes
Route::get('/get-data', [ApiController::class, 'get_data']);

// User routes
Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('/user', [UserController::class, 'getAuthenticatedUser']);

    // API routes
    Route::post('/update-user-avatar', [ApiController::class, 'update_user_avatar']);
    Route::post('/upload-advice', [ApiController::class, 'upload_advice']);
    Route::post('/delete-advice', [ApiController::class, 'delete_advice']);
    Route::post('/update-advice', [ApiController::class, 'update_advice']);
    Route::post('/view-advice', [ApiController::class, 'view_advice']);
    Route::post('/like-advice', [ApiController::class, 'like_advice']);
    Route::post('/report-advice', [ApiController::class, 'report_advice']);
    Route::get('/my-advices', [ApiController::class, 'get_my_advice']);
});