<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiclePickup extends Model
{
  use HasFactory;

  protected $fillable = [
    'vehicle_number',
    'camera_image',
    'images',
    'remarks',
    'employee_id'
  ];

  protected $casts = [
    'images' => 'array',
  ];

  public function drop()
  {
    return $this->hasOne(VehicleDrop::class, 'pickup_id');
  }
  public function employee()
  {
    return $this->belongsTo(Employee::class);
  }
}
