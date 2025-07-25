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
        Schema::create('employee_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');

            $table->string('movement_type'); // e.g., promotion, transfer, demotion
            $table->text('description')->nullable();
            $table->date('effective_date');

            $table->foreignId('from_job_id')->nullable()->constrained('job_posts')->nullOnDelete();
            $table->foreignId('to_job_id')->nullable()->constrained('job_posts')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_movements');
    }
};
