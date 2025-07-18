<?php

use App\Http\Middleware\AktivasiRaport;
use App\Http\Middleware\CekStatusLogin;
use App\Http\Middleware\WalikelasMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \RealRashid\SweetAlert\ToSweetAlert::class,
        ]);
        $middleware->alias([
            'cek-status-login' => CekStatusLogin::class,
            'cek-walikelas' => WalikelasMiddleware::class,
            'aktivasi-raport' => AktivasiRaport::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
