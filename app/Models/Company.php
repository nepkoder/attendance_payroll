<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
  protected $fillable = [
    'name', 'subdomain', 'db_name', 'db_username', 'db_password', 'db_host', 'db_port', 'api_key'
  ];

  // Automatically assign api_key before creating
  protected static function boot()
  {
    parent::boot();

    static::creating(function ($company) {
      if (empty($company->api_key) && !empty($company->subdomain)) {
        $company->api_key = $company->subdomain;
      }
    });
  }
}
