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
        Schema::create('job_job_additionals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('job_id')->constrained()->onDelete('cascade');// job_id = foreignid
            $table->foreignId('job_additional_id')->constrained()->onDelete('cascade');// job_additional_id = foreign
            $table->boolean('required')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_job_additionals');
    }
};
