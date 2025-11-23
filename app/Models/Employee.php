<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
  use Notifiable, HasFactory;

  protected $connection = 'tenant_db';

  protected $fillable = [
    'name', 'username', 'email', 'password', 'phone',
    'company', 'department', 'address', 'document_no',
    'document_image', 'image', 'remarks','status', 'hourly_rate'
  ];

  public function attendances()
  {
    return $this->hasMany(Attendance::class);
  }

  public function pickups()
  {
    return $this->hasMany(VehiclePickup::class);
  }

  public function drop()
  {
    return $this->hasMany(VehicleDrop::class);
  }

  // Relations
//  public function markInLocation()
//  {
//    return $this->belongsTo(Location::class, 'mark_in_location_id');
//  }
//
//  public function markOutLocation()
//  {
//    return $this->belongsTo(Location::class, 'mark_out_location_id');
//  }

  public function markInLocations()
  {
    return $this->belongsToMany(Location::class, 'employee_mark_in_locations');
  }

  public function markOutLocations()
  {
    return $this->belongsToMany(Location::class, 'employee_mark_out_locations');
  }


}
