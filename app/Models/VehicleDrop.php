<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleDrop extends Model
{
  use HasFactory;

  protected $fillable = [
    'pickup_id',
    'camera_image',
    'images',
    'remarks',
  ];

  protected $casts = [
    'images' => 'array',
  ];

  public function pickup()
  {
    return $this->belongsTo(VehiclePickup::class, 'pickup_id');
  }
  public function employee()
  {
    return $this->belongsTo(Employee::class);
  }
}
