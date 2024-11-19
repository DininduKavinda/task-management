<?php

use App\Http\Controllers\Api\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('tasks', TaskController::class);

Route::post('/tasks/{id}/complete', [TaskController::class, 'completeMailer'])->name('completeMailer');

Route::post('/tasks/{id}/pay', [TaskController::class, 'completePayment'])->name('compltePayment');