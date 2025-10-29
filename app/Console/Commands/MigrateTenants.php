<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Models\Company;

class MigrateTenants extends Command
{
  protected $signature = 'migrate:tenants {--company_id=}';
  protected $description = 'Run tenant migrations for all or a specific company';

  public function handle()
  {
    $companies = Company::all();

    if ($this->option('company_id')) {
      $companies = $companies->where('id', $this->option('company_id'));
    }

    foreach ($companies as $company) {
      $this->info("Migrating company: {$company->name}");

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

      Artisan::call('migrate', [
        '--path' => 'database/migrations/tenant',
        '--database' => $connectionName,
        '--force' => true,
      ]);
    }
  }
}

