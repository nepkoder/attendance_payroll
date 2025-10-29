<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
  {
    Schema::table('employees', function (Blueprint $table) {
      $table->enum('status', ['active', 'inactive'])->default('active')->after('username');
      $table->decimal('hourly_rate', 8, 2)->nullable()->after('phone');
    });
  }

  public function down()
  {
    Schema::table('employees', function (Blueprint $table) {
      $table->dropColumn('status');
      $table->dropColumn('hourly_rate');
    });
  }
};
