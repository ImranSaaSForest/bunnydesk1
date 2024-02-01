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
        Schema::create('payruns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('payrun_id')->unique();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('payroll_policy_id')->constrained()->onDelete('cascade');
            $table->foreignId('payroll_payslip_template_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->boolean('is_approved')->default(0);

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->enum('status', ['in progress','cancelled','completed'])->default('in progress');
            $table->enum('payment_interval', ['weekly','biweekly','monthly'])->default('monthly');
            $table->date('start');
            $table->date('end');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payruns');
    }
};
