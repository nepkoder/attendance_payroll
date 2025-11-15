<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\Company;
use Illuminate\Support\Facades\Log;

class DynamicDatabaseSwitcher
{
  public function handle($request, Closure $next)
  {
    $company = null;

    $isApiRequest = $request->expectsJson() || $request->is('api/*') || $request->header('X-Company');
    if ($isApiRequest) {
      // API: header
      $apiKey = $request->header('X-Company');
      $company = Company::where('api_key', $apiKey)->first();
    } else {
      // Web: subdomain
      if ($request->getHost()) {
        $subdomain = explode('.', $request->getHost())[0];
        $company = Company::where('subdomain', $subdomain)->first();
      }
    }
//    $company = Company::first(); // for testing

    // If company not found, return appropriate response
    if (!$company) {
      // Check if it's an API request (e.g., mobile app usually sends JSON)
      if ($isApiRequest) {
        return response()->json([
          'success' => false,
          'message' => 'Invalid company or API key.'
        ], 400);
      }

      // Web request
      return response()->view('errors.no_company');
    }

    if ($company) {
      $connectionName = 'tenant_db';

      Config::set("database.connections.$connectionName", [
        'driver' => 'mysql',
        'host' => $company->db_host,
        'database' => $company->db_name,
        'username' => $company->db_username,
        'password' => $company->db_password,
        'port' => $company->db_port,
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
      ]);

      Config::set('database.default', $connectionName);
      DB::purge($connectionName);
    }

    return $next($request);
  }
}
