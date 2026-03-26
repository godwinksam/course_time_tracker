<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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



use App\Http\Controllers\CourseController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SubSectionController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/course', [CourseController::class, 'index']); // Kept for backward compatibility if needed, or replace with SectionController@index
// But SectionController@index is more RESTful. Let's use it.

Route::apiResource('sections', SectionController::class);
Route::apiResource('sub-sections', SubSectionController::class);

use App\Http\Controllers\ReportController;
Route::get('/report/daily-progress', [ReportController::class, 'dailyProgress']);
Route::get('/report/completed-items', [ReportController::class, 'completedItems']);
