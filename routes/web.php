<?php

use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;

Route::prefix('campaign')->group(function () {
    Route::get('/', [ApplicationController::class, 'create'])->name('application_index');
    Route::post('/', [ApplicationController::class, 'store']);
    Route::get('/complete', [ApplicationController::class, 'complete'])->name('application_complete');

    Route::get('/email/open/{unique_code}', [ApplicationController::class, 'trackEmailOpen'])->name('mail_open');
    Route::get('/view-ticket/{unique_code}', [ApplicationController::class, 'viewTicket'])->name('view_ticket');
    Route::post('/tear-ticket/{unique_code}', [ApplicationController::class, 'tearTicket'])->name('tear_ticket');
    Route::get('/tear-ticket/{unique_code}', [ApplicationController::class, 'viewTearTicket'])->name('view_tear_ticket');

});

require __DIR__.'/auth.php';
