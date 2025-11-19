<?php

use App\Http\Middleware\GuardAuth;
use App\Http\Middleware\TrustProxies;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function () {
    return [
      // Laravel default middleware
      TrustProxies::class,
      StartSession::class,
      ShareErrorsFromSession::class,
      VerifyCsrfToken::class,
    ];
  })
  ->withExceptions(function (Exceptions $exceptions) {
    //
  })->create();
