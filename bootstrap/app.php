<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureRole::class,
            'company.active' => \App\Http\Middleware\EnsureCompanyActive::class,
            'password.changed' => \App\Http\Middleware\EnsurePasswordChanged::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();

// ponytail: on shared hosting the webroot is a sibling `public_html/` (outside this app
// directory) instead of the usual `public/`, so public_path()/@vite() must be redirected
// there too — a no-op locally where no such sibling exists.
if (is_dir($sharedHostingPublic = dirname(__DIR__).'/../public_html')) {
    $app->usePublicPath($sharedHostingPublic);
}

return $app;
