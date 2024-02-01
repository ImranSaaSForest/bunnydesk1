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
        Schema::create('reimbursement_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->enum('status', ['pending','rejected','approved'])->default('pending');
            $table->string('reason')->nullable();
            $table->foreignId('budget_expense_id')->constrained()->onDelete('cascade');
            $table->string('attachment')->nullable();
            $table->longText('comments')->nullable();
            $table->foreignId('processed_by')->nullable();
            $table->foreignId('requested_by')->nullable();
            $table->float('amount');
            $table->foreign('processed_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reimbursement_requests');
    }
};
