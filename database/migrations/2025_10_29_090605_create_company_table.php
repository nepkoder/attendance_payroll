<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up(): void
  {
    Schema::create('companies', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('subdomain')->unique();
      $table->string('db_name')->unique();
      $table->string('db_username');
      $table->string('db_password');
      $table->string('db_host')->default('127.0.0.1');
      $table->integer('db_port')->default(3306);
      $table->string('api_key')->nullable();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('companies');
  }

};
