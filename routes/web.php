<?php

use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WidgetController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/tickets',[TicketController::class, 'index'])->name('admin.tickets.index');
        Route::get('/tickets/{ticket}',[TicketController::class, 'show'])->name('admin.tickets.show');
        Route::post('/tickets/{ticket}',[TicketController::class, 'updateStatus'])->name('admin.tickets.update-status');
    });
    Route::get('/login',[AdminLoginController::class, 'showLoginForm'])->name('admin.login.form');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login');
});

Route::get('widget', [WidgetController::class, 'feedback'])->name('widget.feedback');

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', function () {
    return view('admin.auth.login');
})->name('login');
