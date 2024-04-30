<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\RolApiController;


use App\Http\Controllers\API\PdfApiController;


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

Route::post('login', [AuthController::class,"login"]);
Route::post('signup', [AuthController::class,"signup"]);

Route::post('/upload-pdf', [PdfApiController::class, 'upload']);

Route::apiResource('pdfile', PdfApiController::class);

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::middleware(['auth:api', 'role:Administrator'])->group(function () {
    Route::get('/pdfs', [PdfApiController::class, 'index']);
    Route::post('/pdfs/upload', [PdfApiController::class, 'upload']);
    Route::delete('/pdfs/{id}', [PdfApiController::class, 'destroy']);
});

// Rutas accesibles tanto para Administradores como Usuarios
Route::middleware(['auth:api', 'role:User|Administrator'])->group(function () {
    Route::get('/pdfs/{id}', [PdfApiController::class, 'show']);
    Route::get('/pdfs/download/{id}', [PdfApiController::class, 'download']);
});


