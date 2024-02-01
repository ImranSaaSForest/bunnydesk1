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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');  // company_id = foreign
            $table->unsignedBigInteger('job_id');  // job_id = foreign
            $table->enum('status',['applied', 'shortlisted', 'screened','offer sent','contract accepted','contract sent','offer accepted','offer rejected','on_hold','not_qualified','joined'])->default('applied');
            $table->string('first_name');// first_name = string
            $table->string('last_name');  // last_name = string
            $table->string('email'); // email_address = email
            $table->string('pnone_number'); // phone = string
            $table->string('photo')->nullable(); // image = string
            $table->string('job_accept_code')->unique()->nullable();// job_accept_code = string
            $table->string('rating')->nullable();  // rating = int
            $table->unsignedBigInteger('offer_sent_by')->nullable(); // Foreign key
            $table->timestamp('offer_sent_on')->nullable();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
            $table->foreign('offer_sent_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
            // status = enum - applied, shortlisted, interviewed, offer sent, offer accepted, offer rejected, selected, rejected
            // offer_sent_by = models.ForeignKey(User, on_delete = models.SET_NULL, null = True)
            // offer_sent_on = models.DateTimeField(null = True)
            // create_by = user_id foreign
            // updated_by = user_id foreign
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
