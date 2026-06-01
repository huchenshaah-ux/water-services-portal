<?php

use App\Http\Controllers\Api\ApplicationApiController;
use App\Http\Controllers\Api\ReportApiController;
use App\Http\Controllers\ExcelController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('applications', [ApplicationApiController::class, 'index']);
    Route::post('applications', [ApplicationApiController::class, 'store']);
    Route::put('applications/{application}', [ApplicationApiController::class, 'update']);
    Route::delete('applications/{application}', [ApplicationApiController::class, 'destroy']);

    Route::get('reports', [ReportApiController::class, 'index']);

    Route::post('excel/import', [ExcelController::class, 'import']);
    Route::get('excel/export', [ExcelController::class, 'export']);
});
