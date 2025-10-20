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
      Schema::create('attendances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
        $table->timestamp('mark_in')->nullable();
        $table->timestamp('mark_out')->nullable();
        $table->string('hour')->nullable();
        $table->double('earning')->nullable();
        $table->double('hourly_rate')->nullable();
        $table->decimal('in_latitude', 10, 7)->nullable();
        $table->decimal('in_longitude', 10, 7)->nullable();
        $table->decimal('out_latitude', 10, 7)->nullable();
        $table->decimal('out_longitude', 10, 7)->nullable();
        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
