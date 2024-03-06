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
        Route::get('/createStudy-groups', [StudyGroupController::class, 'createStudyGroup']);
        Route::put('/updateStudy-groups', [StudyGroupController::class, 'updateStudyGroup']);
        Route::get('/indexStudy-groups', [StudyGroupController::class, 'indexStudyGroup']);
        // Route::get('/study-groups/{id}', [StudyGroupController::class, 'showStudyGroupById']);
        Route::delete('/deleteStudy-groups', [StudyGroupController::class, 'deleteStudyGroup']);
        Route::get('/study-group-info/title', [StudyGroupController::class, 'getTitle']);

    });
});
