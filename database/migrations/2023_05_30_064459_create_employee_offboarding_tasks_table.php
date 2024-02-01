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
        Schema::create('employee_offboarding_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_offboarding_id')->constrained()->onDelete('cascade');
            $table->foreignId('offboarding_task_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['notstarted', 'inprogress', 'completed'])->default('notstarted');
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_offboarding_tasks');
    }
};
