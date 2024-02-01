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
        Schema::create('candidate_education', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_id'); // Foreign key to link with candidate
            $table->string('college_name')->default(null)->nullable();
            $table->string('highest_education')->default(null)->nullable();// highest_education = string
            $table->string('branch')->default(null)->nullable();
            $table->string('passed')->default(null)->nullable();
            $table->string('grade')->default(null)->nullable();

            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_education');
    }
};
