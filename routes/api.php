<?php

use App\Http\Controllers\Api\v1\MakeobjController;
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
Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::get('version', [MakeobjController::class, 'version'])->name('version');
    Route::get('capabilities', [MakeobjController::class, 'capabilities'])->name('capabilities');
    Route::post('list', [MakeobjController::class, 'list'])->name('list');
    Route::post('dump', [MakeobjController::class, 'dump'])->name('dump');
    Route::post('pak', [MakeobjController::class, 'pak'])->name('pak');
    Route::post('merge', [MakeobjController::class, 'merge'])->name('merge');
    Route::post('extract', [MakeobjController::class, 'extract'])->name('extract');
});
