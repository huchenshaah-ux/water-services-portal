<?php

declare(strict_types=1);

chdir(dirname(__DIR__));

$prepareStorage = static function (): void {
    $dirs = [
        '/tmp/storage/framework/cache/data',
        '/tmp/storage/framework/sessions',
        '/tmp/storage/framework/views',
        '/tmp/storage/logs',
        '/tmp/views',
        '/tmp/fonts',
        '/tmp/laravel-excel',
    ];
    foreach ($dirs as $dir) {
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
};

$renderError = static function (Throwable $e): void {
    $debug = filter_var(getenv('APP_DEBUG') ?: 'false', FILTER_VALIDATE_BOOLEAN);
    error_log('[water-services-portal] '.$e->getMessage().' in '.$e->getFile().':'.$e->getLine());
    error_log($e->getTraceAsString());
    http_response_code(500);
    header('Content-Type: text/html; charset=utf-8');
    if ($debug) {
        echo '<h1>Application Error</h1><pre>'.htmlspecialchars((string) $e, ENT_QUOTES, 'UTF-8').'</pre>';
    } else {
        echo '<h1>Application Error</h1><p>Check Vercel env vars (APP_KEY, DB_*) and function logs.</p>';
        echo '<p><a href="/ping">Diagnostics: /ping</a></p>';
    }
};

try {
    if (! getenv('APP_KEY')) {
        throw new RuntimeException('APP_KEY is missing. Set it in Vercel → Settings → Environment Variables.');
    }

    if (getenv('VERCEL') || getenv('VERCEL_ENV')) {
        $prepareStorage();
    }

    if (! file_exists(__DIR__.'/../vendor/autoload.php')) {
        throw new RuntimeException('vendor/ not found. Composer install may have failed during deploy (size limit?).');
    }

    require __DIR__.'/../public/index.php';
} catch (Throwable $e) {
    $renderError($e);
}
