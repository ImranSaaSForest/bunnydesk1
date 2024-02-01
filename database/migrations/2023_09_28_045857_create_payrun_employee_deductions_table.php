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
        Schema::create('payrun_employee_deductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payrun_employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('deduction_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('frequency', ['daily', 'weekly', 'monthly']);
            $table->boolean('is_fixed')->default(1);
            $table->float('percentage')->nullable();
            $table->boolean('before_tax')->default(1);
            $table->integer('occurrence');
            $table->double('amount')->default(0);
            $table->boolean('include_in_payrun')->default(1);
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrun_employee_deductions');
    }
};
