<?php

use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\TicketStoreController;

Route::post('tickets', TicketStoreController::class)->name('tickets.store');
Route::get('tickets', StatisticsController::class)->name('tickets.statistics');
