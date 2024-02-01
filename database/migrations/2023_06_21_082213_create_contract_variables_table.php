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
        Schema::create('contract_variables', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('default')->default(0);
            $table->boolean('active')->default(1);
            $table->string('value')->nullable(); //get value if it's a custom variable
            $table->string('path')->nullable(); //get path if it's a default system variable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_variables');
    }
};
