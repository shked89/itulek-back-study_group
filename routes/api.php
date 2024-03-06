<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudyGroupController;
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


Route::prefix('groups')->group(function () {

    Route::prefix('v1')->group(function () {
        Route::get('/create_study-groups', [StudyGroupController::class, 'createStudyGroup']);
        Route::put('/update_study-groups/{id}', [StudyGroupController::class, 'updateStudyGroup']);
        Route::get('/study-groups', [StudyGroupController::class, 'indexStudyGroup']);
        Route::get('/study-groups/{id}', [StudyGroupController::class, 'showStudyGroupById']);
        Route::delete('/study-groups/{id}', [StudyGroupController::class, 'deleteStudyGroup']);
    });
});
