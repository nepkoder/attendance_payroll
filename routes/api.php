<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\VehicleEntryController;
use App\Http\Middleware\DynamicDatabaseSwitcher;
use Illuminate\Support\Facades\Route;

Route::post('/mobile/company', [\App\Http\Controllers\CompanySetupController::class, 'companyList']);

Route::middleware([DynamicDatabaseSwitcher::class])->group(function () {
// API for mobile app
  Route::post('/mobile/login', [EmployeeController::class, 'mobileLogin']);
  Route::post('/mobile/markin', [EmployeeController::class, 'markInMobile']);
  Route::post('/mobile/markout', [EmployeeController::class, 'markOutMobile']);
  Route::post('/mobile/profile', [EmployeeController::class, 'employeeProfile']);
  Route::post('/mobile/dashboard', [EmployeeController::class, 'employeeDasbhoardMobile']);
  Route::post('/mobile/report', [EmployeeController::class, 'employeeReport']);
  Route::post('/mobile/pickup', [VehicleEntryController::class, 'storePickupMobile']);
  Route::post('/mobile/drop', [VehicleEntryController::class, 'storeDropMobile']);
  Route::get('/mobile/pickups', [VehicleEntryController::class, 'pickupList']);

  Route::post('/mobile/upload', [VehicleEntryController::class, 'uploadImage']);

});
