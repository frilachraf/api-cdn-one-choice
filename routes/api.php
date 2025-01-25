<?php

use App\Http\Controllers\CarImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/achraf',function (){
    return 'hello world api';
}
);
Route::post('/files/upload', [FileController::class, 'upload']);   // Upload a file
// Route::get('/files', [FileController::class, 'index']);           // List all files
// Route::get('/files/download/{id}', [FileController::class, 'download']); // Download a file
Route::delete('/files/{path}', [FileController::class, 'destroy']);

// Route::post('/files/upload/', [CarImageController::class, 'uploadCarImages']);   // Upload a file
