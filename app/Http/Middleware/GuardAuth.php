<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class GuardAuth
{
  public function handle($request, Closure $next, string $guard = 'web')
  {
    if (!Auth::guard($guard)->check()) {
      if ($guard === 'employee') {
        return redirect()->route('employee.login');
      }
      return redirect()->route('admin.login');
    }

    return $next($request);
  }
}
