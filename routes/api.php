<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Api\CronjobController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CustomAttendanceLocationController;
use App\Http\Controllers\DailyAttendanceController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

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
            Route::get('/amount-announcement-notifications', [AnnouncementController::class, 'amountAnnouncementNotification']);
            Route::get('/{slug}', [AnnouncementController::class, 'show']);
            Route::delete('/{uuid}', [AnnouncementController::class, 'deleteAnnouncementNotification']);
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
        Route::post('/cronjob', [DailyAttendanceController::class, 'cronjob']);
        Route::middleware(['auth.auth'])->group(function () {
            Route::post('/entry', [DailyAttendanceController::class, 'entry']);
            Route::post('/exit', [DailyAttendanceController::class, 'exit']);
        });
    }
);

Route::group(
    [
        'prefix' => 'calendar'
    ],
    function () {
        Route::middleware(['auth.auth'])->group(function () {
            Route::get('', [CalendarController::class, 'getCalendar']);
        });
    }
);

Route::post('/telegram-bot', function (Request $request) {
    Telegram::bot('mybot')->sendMessage([
        'chat_id' => config('app.telegram_chat_id'),
        'text' => $request->message,
    ]);
});

Route::get('cron-refresh-custom-attendance', [CronjobController::class, 'cronRefreshCustomAttendance']);
