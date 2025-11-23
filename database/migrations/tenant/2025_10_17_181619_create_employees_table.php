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
        Schema::create('employees', function (Blueprint $table) {
          $table->id();
          $table->string('name');
          $table->string('username')->unique();
          $table->string('email')->unique();
          $table->string('password');
          $table->string('phone')->nullable();
          $table->string('company')->nullable();
          $table->string('department')->nullable();
          $table->string('address')->nullable();
          $table->string('document_no')->nullable();
          $table->string('document_image')->nullable();
          $table->string('image')->nullable();
          $table->text('remarks')->nullable();

          // new fields for location references
          $table->unsignedBigInteger('mark_in_location_id')->nullable();
          $table->unsignedBigInteger('mark_out_location_id')->nullable();

          $table->foreign('mark_in_location_id')->references('id')->on('locations')->nullOnDelete();
          $table->foreign('mark_out_location_id')->references('id')->on('locations')->nullOnDelete();

          $table->timestamps();
        });

      // mark_in_locations migration
      Schema::create('employee_mark_in_locations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
        $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
        $table->timestamps();
      });

// mark_out_locations migration
      Schema::create('employee_mark_out_locations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
        $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
        $table->timestamps();
      });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
