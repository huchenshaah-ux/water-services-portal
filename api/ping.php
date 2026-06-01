<?php

header('Content-Type: application/json');

echo json_encode([
    'ok' => true,
    'php' => PHP_VERSION,
    'vendor' => file_exists(__DIR__.'/../vendor/autoload.php'),
    'app_key_set' => ! empty(getenv('APP_KEY')),
    'vercel' => (bool) (getenv('VERCEL') || getenv('VERCEL_ENV')),
], JSON_PRETTY_PRINT);
