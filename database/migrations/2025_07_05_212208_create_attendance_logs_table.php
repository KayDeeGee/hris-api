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
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->dateTime('log_time');
            $table->string('device_name')->nullable();
            $table->unsignedTinyInteger('log_type')->comment('1=IN, 2=OUT');
            $table->unsignedTinyInteger('log_method')->comment('1=QR, 2=Button, 3=RFID, 4=Admin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');
    }
};
