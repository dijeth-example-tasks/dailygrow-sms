<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\SegmentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskRunController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/test', function () {
    return response()->json(['success' => true]);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/segments', [SegmentController::class, 'index']);
    Route::get('/task-runs', [TaskRunController::class, 'index']);

    Route::get('/tasks', [TaskController::class, 'index']);
    Route::get('/tasks/{task}', [TaskController::class, 'show']);
    Route::post('/tasks/create', [TaskController::class, 'store']);
    Route::put('/tasks/edit/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

    Route::get('/clients/{client}', [ClientController::class, 'show']);
    Route::post('/clients/create', [ClientController::class, 'store']);
    Route::put('/clients/edit/{client}', [ClientController::class, 'update']);
    Route::delete('/clients/{client}', [ClientController::class, 'destroy']);
});
