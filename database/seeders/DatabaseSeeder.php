<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    Company::insert([
      [
        'name' => 'UK Company',
        'subdomain' => 'uk',
        'db_name' => 'nepkoder',
        'db_username' => 'root',
        'db_password' => 'sujan123',
        'db_host' => '127.0.0.1',
        'db_port' => 3306,
        'api_key' => 'uk123'
      ],
      [
        'name' => 'US Company',
        'subdomain' => 'us',
        'db_name' => 'us_db',
        'db_username' => 'root',
        'db_password' => 'sujan123',
        'db_host' => '127.0.0.1',
        'db_port' => 3306,
        'api_key' => 'us123'
      ],
    ]);
  }
}
