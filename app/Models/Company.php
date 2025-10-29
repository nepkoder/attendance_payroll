<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
  protected $fillable = [
    'name', 'subdomain', 'db_name', 'db_username', 'db_password', 'db_host', 'db_port', 'api_key'
  ];
}
