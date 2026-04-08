<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\GinzaApplicationController;
use Illuminate\Support\Facades\Route;

Route::prefix('ginza')->group(function () {
    Route::get('/', [GinzaApplicationController::class, 'create'])->name('ginza_index');
    Route::post('/', [GinzaApplicationController::class, 'store']);
    Route::get('/complete', [GinzaApplicationController::class, 'complete'])->name('ginza_complete');

    Route::get('/email/open/{unique_code}', [GinzaApplicationController::class, 'trackEmailOpen'])->name('winner_mail_open');
    Route::get('/view-ticket/{unique_code}', [GinzaApplicationController::class, 'viewTicket'])->name('view_ticket');
    Route::get('/tear-ticket/{unique_code}', [GinzaApplicationController::class, 'tearTicket'])->name('tear_ticket');
});


Route::prefix('stores-exclude')->group(function () {
    Route::get('/', [ApplicationController::class, 'create'])->name('stores_exclude_index');
    Route::post('/', [ApplicationController::class, 'store']);
    Route::get('/complete', [ApplicationController::class, 'complete'])->name('stores_exclude_complete');

    Route::get('/email/open/{unique_code}', [ApplicationController::class, 'trackEmailOpen'])->name('winner_mail_open');
    Route::get('/view-ticket/{unique_code}', [ApplicationController::class, 'viewTicket'])->name('view_ticket');
    Route::get('/tear-ticket/{unique_code}', [ApplicationController::class, 'tearTicket'])->name('tear_ticket');
});

require __DIR__.'/auth.php';
