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
        Schema::create('companies', function (Blueprint $table) {
            // basic
            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->string('company_type')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('registration_id')->nullable();
            $table->string('currency')->nullable();
            $table->decimal('latitude', 20, 7)->nullable();
            $table->decimal('longitude', 21, 7)->nullable();
            $table->string('timezone')->nullable();
            $table->boolean('is_primary')->default(false);

            // contact
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email');
            $table->string('website')->nullable();

            // adress
            $table->string('street')->nullable();
            $table->string('street2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('country');

            // social media
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('youtube')->nullable();
            $table->string('instagram')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
