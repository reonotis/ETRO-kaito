<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
//    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
//    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::prefix('admin')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::put('password', [PasswordController::class, 'update'])->name('password.update');
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

        Route::get('/dashboard', [App\Http\Controllers\Admin\ApplicationController::class, 'dashboard'])->name('admin_dashboard');
        Route::get('/list/{type}', [App\Http\Controllers\Admin\ApplicationController::class, 'list'])->name('admin_list');
        Route::get('/applications/data', [App\Http\Controllers\Admin\ApplicationController::class, 'getData'])->name('applications.data');
        Route::get('/applications/download-csv', [App\Http\Controllers\Admin\ApplicationController::class, 'downloadCsv'])->name('applications.download-csv');
        Route::post('/applications/update-visit-schedule', [App\Http\Controllers\Admin\ApplicationController::class, 'updateVisitSchedule'])->name('applications.update-visit-schedule');
        Route::post('/applications/send-winner-mail', [App\Http\Controllers\Admin\ApplicationController::class, 'sendWinnerMail'])->name('applications.send-winner-mail');
        Route::post('/applications/send-reminder-mail', [App\Http\Controllers\Admin\ApplicationController::class, 'sendReminderMail'])->name('applications.send-reminder-mail');
    });
});

