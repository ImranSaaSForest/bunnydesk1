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
        Schema::create('policy_leave_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_type_id')->constrained()->onDelete('cascade');
            $table->integer('days_allowed');
            $table->enum('frequency', ['daily', 'weekly', 'monthly','annually']);
            $table->date('last_reset_date')->nullable();
            $table->boolean('is_paid')->default(0);
            $table->boolean('negative_leave_balance')->default(0);

            $table->foreignId('policy_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policy_leave_types');
    }
};
