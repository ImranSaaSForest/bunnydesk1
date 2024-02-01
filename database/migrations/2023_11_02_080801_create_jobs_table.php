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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();

            $table->string('title');// title = string
            $table->enum('job_status', ['open','draft','closed','forward','approved','rejected','admin rejected']);// job_status_id = enum - draft, approved, open, closed
            $table->unsignedBigInteger('hiring_lead_id'); // hiring_lead_id = user_id foreign
            // $table->foreignId('onboarding_list_id')->constrained()->onDelete('cascade');
            $table->foreignId('employee_type_id')->nullable()->constrained()->onDelete('set null'); // employee_type_id = foreign
            $table->foreignId('designation_id')->nullable()->constrained()->onDelete('set null'); // designation_id = foreign
            $table->text('description');// description = text
            $table->string('city');// city = string
            $table->string('province');// province = province
            $table->string('postal_code');// postal_code = string
            $table->string('country'); // country_id = foreign
            $table->string('salary');// salary = string
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->date('interview_date');// interview_date = date
            $table->foreignId('created_by')->nullable();// created_by = user_id foreign
            $table->foreignId('updated_by')->nullable();// update_by = user_id foreign
            $table->foreignId('approved_by')->nullable();// approved_by = user_id foreign

            $table->foreign('hiring_lead_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
