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
        Schema::create('appraisal_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('performance_goal_id')->constrained()->onDelete('cascade');
            $table->string('message');
            $table->boolean('default')->default(0);
            $table->date('date');
            $table->foreignId('created_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appraisal_messages');
    }
};
