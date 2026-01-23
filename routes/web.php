<?php

use App\Http\Controllers\WidgetController;
use Illuminate\Support\Facades\Route;

Route::get('widget', [WidgetController::class, 'feedback'])->name('widget.feedback');

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', function(){
    return redirect()->to('/admin/login');
})->name('login');
