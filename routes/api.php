<?php

use App\Http\Controllers\AlertController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ConversionController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (no auth required)
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Currency list and rates are public
Route::get('/currencies',                              [CurrencyController::class, 'index']);
Route::get('/currencies/{base}/rates',                 [CurrencyController::class, 'rates']);
Route::get('/currencies/{base}/rate/{target}',         [CurrencyController::class, 'singleRate']);
Route::get('/currencies/{base}/historical/{target}',   [CurrencyController::class, 'historical']);

// Convert is public but saves history only when authenticated
Route::post('/convert', [ConversionController::class, 'convert']);

// News is public
Route::get('/news',              [NewsController::class, 'index']);
Route::get('/news/{currency}',   [NewsController::class, 'byCurrency']);

// Feedback submit is public — user linked via optional token below

/*
|--------------------------------------------------------------------------
| Protected Routes (Sanctum token required)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);
    Route::put('/me',      [AuthController::class, 'updatePreferences']);

    // Conversion history
    Route::get('/history',         [ConversionController::class, 'history']);
    Route::delete('/history',      [ConversionController::class, 'clearHistory']);
    Route::delete('/history/{id}', [ConversionController::class, 'deleteHistory']);

    // Rate alerts
    Route::get('/alerts',         [AlertController::class, 'index']);
    Route::post('/alerts',        [AlertController::class, 'store']);
    Route::put('/alerts/{id}',    [AlertController::class, 'update']);
    Route::delete('/alerts/{id}', [AlertController::class, 'destroy']);

    // Notification preferences + FCM device token
    Route::get('/notifications/preferences',  [NotificationController::class, 'getPreferences']);
    Route::put('/notifications/preferences',  [NotificationController::class, 'updatePreferences']);
    Route::post('/notifications/device-token',[NotificationController::class, 'saveDeviceToken']);

    // Feedback — submit (auth required so user is always linked) + view own
    Route::post('/feedback', [FeedbackController::class, 'store']);
    Route::get('/feedback',  [FeedbackController::class, 'myFeedback']);
});
