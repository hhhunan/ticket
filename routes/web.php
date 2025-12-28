<?php

use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });
    Route::get('/login',[AdminLoginController::class, 'showLoginForm'])->name('admin.login.form');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login');
    Route::get('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
});

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function () {

//    Route::get('/', [HomeController::class, 'index']);
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('welcome');

    Route::get('billing', function () {
        return view('admin.billing');
    })->name('billing');

    Route::get('profile', function () {
        return view('admin.profile');
    })->name('profile');

    Route::get('rtl', function () {
        return view('rtl');
    })->name('rtl');

    Route::get('user-management', function () {
        return view('admin.laravel-examples/user-management');
    })->name('user-management');

    Route::get('tables', function () {
        return view('admin.tables');
    })->name('tables');

    Route::get('virtual-reality', function () {
        return view('admin.virtual-reality');
    })->name('virtual-reality');

    Route::get('static-sign-in', function () {
        return view('admin.static-sign-in');
    })->name('sign-in');

    Route::get('static-sign-up', function () {
        return view('admin.static-sign-up');
    })->name('sign-up');

    Route::get('/logout', [SessionsController::class, 'destroy']);
    Route::get('/user-profile', [InfoUserController::class, 'create']);
    Route::post('/user-profile', [InfoUserController::class, 'store']);
    Route::get('/login', function () {
        return view('admin.dashboard');
    })->name('sign-up');
});

//Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
