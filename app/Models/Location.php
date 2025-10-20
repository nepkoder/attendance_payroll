<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
  use HasFactory;

  protected $fillable = [
    'alias',
    'latitude',
    'longitude',
    'remarks',
  ];
}
