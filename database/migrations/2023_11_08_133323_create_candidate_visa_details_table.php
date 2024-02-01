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
        Schema::create('candidate_visa_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_id');
            $table->string('visa_type')->nullable(); // Visa type, e.g., tourist, student, work, etc.
            $table->string('visa_number')->nullable();
            $table->string('country_of_origin')->default(null)->nullable(); // Country of origin for the visa applicant
            $table->string('passport_number')->default(null)->nullable()->unique(); // Unique passport number
            $table->string('visa_path')->default(null)->nullable();
            $table->string('nationalid_number')->default(null)->nullable(); // Visa issue date
            $table->date('expiration_date')->default(null)->nullable(); // Visa expiration date
            // $table->boolean('is_valid')->default(true);
            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_visa_details');
    }
};
