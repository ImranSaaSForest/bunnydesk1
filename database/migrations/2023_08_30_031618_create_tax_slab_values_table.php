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
        Schema::create('tax_slab_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tax_slab_id')->constrained()->onDelete('cascade');
            $table->integer('start');
            $table->integer('end')->nullable();
            $table->enum('cal_range',['To', 'And Above']);
            $table->integer('fixed_amount')->nullable();
            $table->float('percentage')->nullable();
            $table->longText('description')->nullable();
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_slab_values');
    }
};
