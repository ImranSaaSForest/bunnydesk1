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
        Schema::create('candidate_additionals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');  // candidate_id = foreign
            $table->string('resume_path')->default(null)->nullable(); // resume = string
            $table->string('desired_salary')->default(null)->nullable(); // desired_salary = string
            $table->longText('cover_letter')->default(null)->nullable();  // cover_letter = string
            $table->string('linkedin_url')->default(null)->nullable();// linkedin_url = url
            $table->string('referredby')->default(null)->nullable();// college_university = string
            $table->string('reference')->default(null)->nullable();// college_university = string
            // $table->string('joining_date')->default(null)->nullable();  // date_available = date
            $table->string('website_blog_portfolio')->default(null)->nullable();   // website_blog_portfolio = url
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_additionals');
    }
};
