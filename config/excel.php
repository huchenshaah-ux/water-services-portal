<?php

use Maatwebsite\Excel\Excel;

return [
    'exports' => [
        'chunk_size' => 1000,
        'pre_calculate_formulas' => false,
    ],
    'imports' => [
        'read_only' => true,
        'heading_row' => ['formatter' => 'slug'],
    ],
    'extension_detector' => [
        'xlsx' => Excel::XLSX,
        'xls' => Excel::XLS,
        'csv' => Excel::CSV,
    ],
    'temporary_files' => [
        'local_path' => env('VERCEL') || env('VERCEL_ENV')
            ? '/tmp/laravel-excel'
            : storage_path('framework/cache/laravel-excel'),
    ],
];
