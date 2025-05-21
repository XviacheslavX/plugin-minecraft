<?php

use Azuriom\Plugin\Centralcorp\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Route;
use Azuriom\Plugin\Centralcorp\Controllers\Api\FileController;
use Azuriom\Plugin\Centralcorp\Controllers\Api\ModController;

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

Route::get('/options', [ApiController::class, 'getOptions']);
Route::get('/files', [FileController::class, 'index']);
Route::get('/mods', [ModController::class, 'index']);
