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
        Schema::create('leave_credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('leave_type_id')->constrained()->onDelete('cascade'); // vacation, sick, personal, etc.
            $table->integer('total_credits')->default(0); // Total allocated credits
            $table->integer('used_credits')->default(0); // Credits already used
            $table->integer('year'); // Year these credits apply to
            $table->date('valid_from'); // When credits become valid
            $table->date('valid_until'); // When credits expire
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Add indexes for better performance
            $table->index(['user_id', 'year']);
            $table->index(['user_id', 'leave_type_id']);
            $table->index('is_active');

            $table->unique(['user_id', 'leave_type_id', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_credits');
    }
};
