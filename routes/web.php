<?php

use App\Http\Controllers\GinzaApplicationController;
use App\Http\Controllers\HankyuApplicationController;
use App\Http\Controllers\TicketInfoController;
use Illuminate\Support\Facades\Route;

Route::prefix('ginza')->group(function () {
    Route::get('/', [GinzaApplicationController::class, 'create'])->name('ginza_index');
    Route::post('/', [GinzaApplicationController::class, 'store']);
    Route::get('/complete', [GinzaApplicationController::class, 'complete'])->name('ginza_complete');

    Route::get('/email/open/{unique_code}', [GinzaApplicationController::class, 'trackEmailOpen'])->name('ginza_winner_mail_open');
    Route::get('/view-ticket/{unique_code}', [GinzaApplicationController::class, 'viewTicket'])->name('ginza_view_ticket');
    Route::get('/tear-ticket/{unique_code}', [GinzaApplicationController::class, 'tearTicket'])->name('ginza_tear_ticket');
});


Route::prefix('hankyu')->group(function () {
    Route::get('/', [HankyuApplicationController::class, 'create'])->name('hankyu_index');
    Route::post('/', [HankyuApplicationController::class, 'store']);
    Route::get('/complete', [HankyuApplicationController::class, 'complete'])->name('hankyu_complete');

    Route::get('/email/open/{unique_code}', [HankyuApplicationController::class, 'trackEmailOpen'])->name('hankyu_winner_mail_open');
    Route::get('/view-ticket/{unique_code}', [HankyuApplicationController::class, 'viewTicket'])->name('hankyu_view_ticket');
    Route::get('/tear-ticket/{unique_code}', [HankyuApplicationController::class, 'tearTicket'])->name('hankyu_tear_ticket');
});

Route::prefix('ticket-info')->group(function () {
    Route::get('/', [TicketInfoController::class, 'index'])->name('ticket_info');
});

require __DIR__.'/auth.php';
