<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
  use HasFactory;

  protected $fillable = [
    'employee_id',
    'mark_in',
    'mark_out',
    'in_latitude',
    'in_longitude',
    'out_latitude',
    'out_longitude',
    'hour',
    'hourly_rate',
    'earning'
  ];

  protected $casts = [
    'mark_in' => 'datetime',
    'mark_out' => 'datetime',
  ];

  public function employee()
  {
    return $this->belongsTo(Employee::class);
  }
}
