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
        //
        Schema::table('job_applications', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('cover_letter')->nullable();
            $table->string('resume_path')->nullable();
            $table->enum('status', ['pending', 'reviewed', 'shortlisted', 'hired', 'rejected'])->default('pending');
            $table->timestamp('applied_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('job_applications', function (Blueprint $table) {
            // $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id',
                'cover_letter',
                'resume_path',
                'status',
                'applied_at',
            ]);
        });
    }
};
