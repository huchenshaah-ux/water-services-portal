<?php

/**
 * Vercel serverless entry point.
 */
chdir(dirname(__DIR__));

// Writable storage on Vercel (read-only filesystem)
if (getenv('VERCEL') || getenv('VERCEL_ENV')) {
    $dirs = [
        '/tmp/storage/framework/cache/data',
        '/tmp/storage/framework/sessions',
        '/tmp/storage/framework/views',
        '/tmp/storage/logs',
        '/tmp/views',
    ];
    foreach ($dirs as $dir) {
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}

require __DIR__.'/../public/index.php';
