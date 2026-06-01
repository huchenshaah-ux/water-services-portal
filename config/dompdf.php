<?php

return [
    'show_warnings' => false,
    'public_path' => public_path(),
    'convert_entities' => true,
    'options' => [
        'font_dir' => env('VERCEL') || env('VERCEL_ENV') ? '/tmp/fonts' : storage_path('fonts'),
        'font_cache' => env('VERCEL') || env('VERCEL_ENV') ? '/tmp/fonts' : storage_path('fonts'),
        'temp_dir' => env('VERCEL') || env('VERCEL_ENV') ? '/tmp' : sys_get_temp_dir(),
        'chroot' => realpath(base_path()),
    ],
];
