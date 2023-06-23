<?php

use App\Http\Controllers\EmployeeController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(
    [
        'prefix' => 'employee'
    ],
    function () {
        Route::post('/login', [EmployeeController::class, 'login']);
        Route::middleware(['auth.auth'])->group(function () {
            Route::get('/user', [EmployeeController::class, 'getCurrentUser']);
            Route::post('/update', [EmployeeController::class, 'update']);
        });
        Route::post('/forgot-password', [EmployeeController::class, 'forgotPassword']);
        Route::post('/check-token', [EmployeeController::class, 'checkToken']);
    }
);
