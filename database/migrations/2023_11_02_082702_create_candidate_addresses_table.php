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
        Schema::create('candidate_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade'); // candidate_id = foreign
            $table->string('flat_no')->default(null)->nullable(); // flat_no = string
            $table->string('street_1')->default(null)->nullable(); // street_1 = string
            $table->string('street_2')->default(null)->nullable();// street_2 = string
            $table->string('city')->default(null)->nullable();// city = string
            $table->string('province')->default(null)->nullable();// province = string
            $table->string('postal_code')->default(null)->nullable();  // postal_code = string
            $table->string('country')->default(null)->nullable();// country = string
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_addresses');
    }
};
