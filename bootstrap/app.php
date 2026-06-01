<?php

use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$storagePath = (getenv('VERCEL') || getenv('VERCEL_ENV'))
    ? '/tmp/storage'
    : dirname(__DIR__).'/storage';

return Application::configure(basePath: dirname(__DIR__))
    ->useStoragePath($storagePath)
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => EnsureUserHasRole::class,
            'locale' => SetLocale::class,
        ]);
        $middleware->web(append: [
            SetLocale::class,
        ]);
        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(fn () => route('dashboard'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
