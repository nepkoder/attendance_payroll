<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\VehicleEntryController;
use Illuminate\Support\Facades\Route;

// API for mobile app
Route::post('/mobile/login', [EmployeeController::class, 'mobileLogin']);
Route::post('/mobile/markin', [EmployeeController::class, 'markInMobile']);
Route::post('/mobile/markout', [EmployeeController::class, 'markOutMobile']);
Route::post('/mobile/profile', [EmployeeController::class, 'employeeProfile']);
Route::post('/mobile/dashboard', [EmployeeController::class, 'employeeDasbhoardMobile']);
Route::post('/mobile/report', [EmployeeController::class, 'employeeReport']);
Route::post('/mobile/pickup', [VehicleEntryController::class, 'storePickupMobile']);
Route::post('/mobile/drop', [VehicleEntryController::class, 'storeDropMobile']);



