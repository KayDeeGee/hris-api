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
        Schema::create('employee_reports_to', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade'); // subordinate
            $table->foreignId('manager_id')->constrained('employees')->onDelete('cascade'); // manager
            $table->enum('relationship_type', ['direct', 'project', 'functional', 'temporary'])->default('direct');
            $table->timestamps();

            $table->unique(['employee_id', 'manager_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_reports_to');
    }
};
