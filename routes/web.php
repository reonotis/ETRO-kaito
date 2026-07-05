<?php

use App\Http\Controllers\HankyuApplicationController;
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


Route::prefix('hankyu')->group(function () {
    Route::get('/', [HankyuApplicationController::class, 'create'])->name('hankyu_index');
    Route::post('/', [HankyuApplicationController::class, 'store']);
    Route::get('/complete', [HankyuApplicationController::class, 'complete'])->name('hankyu_complete');
});

require __DIR__.'/auth.php';
