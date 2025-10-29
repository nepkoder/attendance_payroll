<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CompanySetupController extends Controller
{
  public function index()
  {
    $companies = Company::all();
    return view('companies', compact('companies'));
  }
  public function companyList() {
    $companies = Company::all();
    return response()->json($companies);
  }

  public function store(Request $request)
  {
    $request->validate([
      'name'=>'required',
      'subdomain'=>'required|unique:companies',
      'db_name'=>'required|unique:companies',
      'db_username'=>'required',
      'db_password'=>'required',
      'db_host'=>'required',
      'db_port'=>'required|integer',
    ]);

    $company = Company::create($request->all());

    // Create tenant database
    DB::statement("CREATE DATABASE IF NOT EXISTS `{$company->db_name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    // Migrate tenant tables
    $connectionName = 'tenant_db';
    Config::set("database.connections.$connectionName", [
      'driver'=>'mysql',
      'host'=>$company->db_host,
      'database'=>$company->db_name,
      'username'=>$company->db_username,
      'password'=>$company->db_password,
      'port'=>$company->db_port,
      'charset'=>'utf8mb4',
      'collation'=>'utf8mb4_unicode_ci',
    ]);
    Config::set('database.default', $connectionName);
    DB::purge($connectionName);

    Artisan::call('migrate', [
      '--path'=>'database/migrations/tenant',
      '--database'=>$connectionName,
      '--force'=>true
    ]);

    DB::connection($connectionName)->table('users')->insert([
      'name' => $request->admin_name ?? 'admin',
      'email' => $request->admin_email ?? 'admin',
      'username' => $request->admin_email ?? 'username',
      'password' => Hash::make($request->admin_password),
      'created_at' => now(),
      'updated_at' => now(),
    ]);
    Config::set('database.default', env('DB_CONNECTION', 'mysql'));

    return redirect()->route('companies.index')->with('success','Company created successfully!');
  }

  public function edit($id)
  {
    $company = Company::findOrFail($id);
    return view('companies', compact('company'));
  }

  public function update(Request $request, $id)
  {
    $company = Company::findOrFail($id);
    $company->update($request->all());
    return redirect()->route('companies.index')->with('success','Company updated successfully!');
  }

  public function destroy($id)
  {
    $company = Company::findOrFail($id);

    // Optional: drop tenant database
    // DB::statement("DROP DATABASE IF EXISTS `{$company->db_name}`");

    $company->delete();
    return redirect()->route('companies.index')->with('success','Company deleted successfully!');
  }
}
