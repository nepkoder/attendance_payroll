<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
  /**
   * The URIs that should be excluded from CSRF verification.
   *
   * @var array<int, string>
   */
  protected $except = [
    // You can temporarily disable CSRF for all routes to fix 419 errors:
     '*'

    // Or disable for specific routes:
    // 'webhook/*', 'api/payment/callback'
  ];
}
