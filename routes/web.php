<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('applications', ApplicationController::class);
    Route::get('applications/{application}/pdf', [ApplicationController::class, 'pdf'])->name('applications.pdf');
    Route::get('applications/{application}/qr', [ApplicationController::class, 'qr'])->name('applications.qr');

    Route::get('excel/import', [ExcelController::class, 'importForm'])->name('excel.import.form');
    Route::post('excel/import', [ExcelController::class, 'import'])->name('excel.import');
    Route::get('excel/export', [ExcelController::class, 'export'])->name('excel.export');

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/daily', [ReportController::class, 'daily'])->name('reports.daily');
    Route::get('reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
    Route::get('reports/connections', [ReportController::class, 'connections'])->name('reports.connections');
    Route::get('reports/categories', [ReportController::class, 'categories'])->name('reports.categories');
    Route::get('reports/print', [ReportController::class, 'print'])->name('reports.print');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');

    Route::middleware('role:admin,supervisor')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });
});
