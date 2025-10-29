<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CompanySetupController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\VehicleEntryController;
use App\Http\Middleware\GuardAuth;
use Illuminate\Support\Facades\Route;


Route::get('/companies', [CompanySetupController::class, 'index'])->name('companies.index');
Route::post('/companies', [CompanySetupController::class, 'store'])->name('companies.store');
Route::get('/companies/{id}/edit', [CompanySetupController::class, 'edit'])->name('companies.edit');
Route::put('/companies/{id}', [CompanySetupController::class, 'update'])->name('companies.update');
Route::delete('/companies/{id}', [CompanySetupController::class, 'destroy'])->name('companies.destroy');

Route::middleware([DynamicDatabaseSwitcher::class])->group(function () {

// Main Page Route
  Route::get('/', [AdminController::class, 'showLoginForm'])->name('login');

// Admin
  Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

    // protected route
    Route::middleware([GuardAuth::class . ':web'])->group(function () {
      Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

      Route::get('/employee', [EmployeeController::class, 'index'])->name('employee.index');
      Route::get('/employee/create', [EmployeeController::class, 'create'])->name('employee.create');
      Route::post('/employee/store', [EmployeeController::class, 'store'])->name('employee.store');
      Route::get('/employee/edit/{id}', [EmployeeController::class, 'edit'])->name('employee.edit');
      Route::post('/employee/update/{id}', [EmployeeController::class, 'update'])->name('employee.update');
      Route::delete('/employee/delete/{id}', [EmployeeController::class, 'destroy'])->name('employee.delete');
      Route::get('/employee/{id}/view', [EmployeeController::class, 'show'])->name('employee.view');

      Route::get('/location', [LocationController::class, 'index'])->name('location.list');
      Route::get('/location/add', [LocationController::class, 'create'])->name('location.add');
      Route::post('/location/store', [LocationController::class, 'store'])->name('location.store');
      Route::get('/location/edit/{id}', [LocationController::class, 'edit'])->name('location.edit');
      Route::post('/location/update/{id}', [LocationController::class, 'update'])->name('location.update');
      Route::delete('/location/delete/{id}', [LocationController::class, 'destroy'])->name('location.delete');

      Route::get('/hourly-rate', [EmployeeController::class, 'hourlyRateList'])->name('employee.hourlyRateList');
      Route::post('/hourly-rate/update', [EmployeeController::class, 'updateHourlyRate'])->name('employee.updateHourlyRate');

      Route::get('/employee-report', [AdminController::class, 'employeeReport'])->name('admin.employee.report');
      Route::get('/attendance-report', [AdminController::class, 'attendanceReport'])->name('admin.attendance.report');
      Route::get('/support', [AdminController::class, 'supportHelp'])->name('admin.support');
      Route::get('/employee/summary/{employee}', [AdminController::class, 'employeeSummary'])->name('employee.summary');

      Route::get('/setting', [AdminController::class, 'setting'])->name('admin.setting');
      Route::post('/update-setting', [AdminController::class, 'updateSetting'])->name('settings.update');

    });


  });


// Employee
  Route::get('/employee/login', [EmployeeController::class, 'showLoginForm'])->name('employee.login');
  Route::post('/employee/login', [EmployeeController::class, 'login'])->name('employee.login.submit');
  Route::post('/employee/logout', [EmployeeController::class, 'logout'])->name('employee.logout');

  Route::middleware([\App\Http\Middleware\GuardAuth::class . ':employee'])->group(function () {

    Route::prefix('employee')->group(function () {
      Route::get('/dashboard', [EmployeeController::class, 'dashboard'])->name('employee.dashboard');
      Route::get('/pickup', [EmployeeController::class, 'pickup'])->name('employee.pickup');
      Route::get('/drop', [EmployeeController::class, 'drop'])->name('employee.drop');
      Route::get('/attendance-report', [EmployeeController::class, 'attendanceReport'])->name('employee.attendance');
      Route::get('/pd-report', [EmployeeController::class, 'pdReport'])->name('employee.pdreport');
      Route::get('/earnings-report', [EmployeeController::class, 'earningsReport'])->name('employee.earnings');
      Route::get('/profile', [EmployeeController::class, 'profileEdit'])->name('employee.profile.edit');
      Route::post('/profile', [EmployeeController::class, 'updateProfile'])->name('employee.profile.update');
      Route::post('/profile/password', [EmployeeController::class, 'changePassword'])->name('employee.profile.password');
    });


    Route::post('/employee/attendance/mark-in', [AttendanceController::class, 'markIn'])->name('employee.attendance.markIn');
    Route::post('/employee/attendance/mark-out', [AttendanceController::class, 'markOut'])->name('employee.attendance.markOut');

    Route::get('/vehicle-entry', [VehicleEntryController::class, 'index'])->name('vehicle.entry');
    Route::post('/vehicle-entry/pickup', [VehicleEntryController::class, 'storePickup'])->name('vehicle.pickup.store');
    Route::post('/vehicle-entry/drop', [VehicleEntryController::class, 'storeDrop'])->name('vehicle.drop.store');

  });

});


