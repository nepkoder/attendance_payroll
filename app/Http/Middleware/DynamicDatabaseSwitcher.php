<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\Company;

class DynamicDatabaseSwitcher
{
  public function handle($request, Closure $next)
  {
    $company = null;

    // Web: subdomain
//    if ($request->getHost()) {
//      $subdomain = explode('.', $request->getHost())[0];
//      $company = Company::where('subdomain', $subdomain)->first();
//    }
    $company = Company::find(1);


    // API: header
    if (!$company && $request->header('X-Company')) {
      $apiKey = $request->header('X-Company');
      $company = Company::where('api_key', $apiKey)->first();
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
