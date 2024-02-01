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
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('company_name', 250)->nullable();
            $table->string('designation', 100)->nullable();
            $table->string('salary', 20)->nullable();
            $table->date('exp_from')->nullable();
            $table->date('exp_to')->nullable();
            $table->string('reference_name', 50)->nullable();
            $table->string('reference_phone', 20)->nullable();
            $table->string('description', 250)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
