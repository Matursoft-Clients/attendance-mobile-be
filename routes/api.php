<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CustomAttendanceLocationController;
use App\Http\Controllers\DailyAttendanceController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SettingController;
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
        Route::post('/reset-password', [EmployeeController::class, 'resetPassword']);
    }
);

Route::group(
    [
        'prefix' => 'announcements'
    ],
    function () {
        Route::middleware(['auth.auth'])->group(function () {
            Route::get('/', [AnnouncementController::class, 'index']);
            Route::get('/{slug}', [AnnouncementController::class, 'show']);
        });
    }
);

Route::group(
    [
        'prefix' => 'banners'
    ],
    function () {
        Route::middleware(['auth.auth'])->group(function () {
            Route::get('/', [BannerController::class, 'index']);
        });
    }
);

Route::group(
    [
        'prefix' => 'settings'
    ],
    function () {
        Route::get('/', [SettingController::class, 'index']);
    }
);


Route::group(
    [
        'prefix' => 'custom-attendance-locations'
    ],
    function () {
        Route::middleware(['auth.auth'])->group(function () {
            Route::get('/', [CustomAttendanceLocationController::class, 'getCustomAttendance']);
        });
    }
);

Route::group(
    [
        'prefix' => 'daily-attendances'
    ],
    function () {
        Route::middleware(['auth.auth'])->group(function () {
            Route::get('/', [DailyAttendanceController::class, 'dailyAttendances']);
        });
    }
);

Route::group(
    [
        'prefix' => 'presence'
    ],
    function () {
        Route::middleware(['auth.auth'])->group(function () {
            Route::post('/entry', [DailyAttendanceController::class, 'entry']);
            Route::post('/exit', [DailyAttendanceController::class, 'exit']);
        });
    }
);
