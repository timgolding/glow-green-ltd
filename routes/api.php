<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoilerController;

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
Route::get('/test2', function () {
    return response()->json(['status' => 'working']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/boilers/trashed', [BoilerController::class, 'trashed']); // view the trash
    Route::get('/boilers', [BoilerController::class, 'index']); // List boilers
    Route::get('/boilers/{id}', [BoilerController::class, 'show']); // Get one boiler
    Route::post('/boilers/{id}/restore', [BoilerController::class, 'restore']); // restore from trash
    Route::post('/boilers', [BoilerController::class, 'store']); // Create
    Route::put('/boilers/{id}', [BoilerController::class, 'update']); // Update
    Route::delete('/boilers/{id}', [BoilerController::class, 'destroy']); // Soft delete
});

Route::get('/ping', function () {
    die(' API Route Hit!');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
