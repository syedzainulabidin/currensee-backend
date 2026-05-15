<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminFeedbackController;
use App\Http\Controllers\Admin\AdminNewsController;
use App\Http\Controllers\Admin\AdminUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Auth
|--------------------------------------------------------------------------
*/

Route::get('/',          fn() => redirect()->route('admin.login'));
Route::get('/login',     [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/login',    [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/logout',   [AdminAuthController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Admin Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/users',                   [AdminUserController::class, 'index'])->name('admin.users');
    Route::get('/users/{id}',              [AdminUserController::class, 'show'])->name('admin.users.show');
    Route::post('/users/{id}/suspend',     [AdminUserController::class, 'suspend'])->name('admin.users.suspend');
    Route::post('/users/{id}/unsuspend',   [AdminUserController::class, 'unsuspend'])->name('admin.users.unsuspend');
    Route::delete('/users/{id}',           [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/news',          [AdminNewsController::class, 'index'])->name('admin.news');
    Route::post('/news/refresh', [AdminNewsController::class, 'refresh'])->name('admin.news.refresh');
    Route::delete('/news/{id}',  [AdminNewsController::class, 'destroy'])->name('admin.news.destroy');

    Route::get('/feedback',              [AdminFeedbackController::class, 'index'])->name('admin.feedback');
    Route::post('/feedback/{id}/status', [AdminFeedbackController::class, 'updateStatus'])->name('admin.feedback.status');
    Route::delete('/feedback/{id}',      [AdminFeedbackController::class, 'destroy'])->name('admin.feedback.destroy');

    Route::get('/conversions',   [AdminDashboardController::class, 'conversions'])->name('admin.conversions');
    Route::get('/alerts',        fn() => view('admin.alerts', ['alerts' => \App\Models\RateAlert::with('user:id,name')->orderByDesc('created_at')->paginate(20)]))->name('admin.alerts');
    Route::get('/notifications', fn() => view('admin.notifications'))->name('admin.notifications');
});

/*
|--------------------------------------------------------------------------
| User App Routes (SPA-style, JS fetches API)
|--------------------------------------------------------------------------
*/

Route::prefix('app')->group(function () {
    Route::get('/login',      fn() => view('user.login'))->name('app.login');
    Route::get('/register',   fn() => view('user.register'))->name('app.register');
    Route::get('/converter',  fn() => view('user.converter'))->name('app.converter');
    Route::get('/currencies', fn() => view('user.currencies'))->name('app.currencies');
    Route::get('/history',    fn() => view('user.history'))->name('app.history');
    Route::get('/alerts',     fn() => view('user.alerts'))->name('app.alerts');
    Route::get('/news',       fn() => view('user.news'))->name('app.news');
    Route::get('/profile',    fn() => view('user.profile'))->name('app.profile');
    Route::get('/feedback',   fn() => view('user.feedback'))->name('app.feedback');
    Route::get('/help',       fn() => view('user.help'))->name('app.help');
});
