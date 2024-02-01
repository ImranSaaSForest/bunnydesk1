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
       // Remove foreign key from tax_slabs table
       Schema::table('tax_slabs', function (Blueprint $table) {
        $table->dropForeign(['company_id']);
         $table->dropColumn('company_id');
    });

    // Remove foreign key from over_time_rates table
    Schema::table('over_time_rates', function (Blueprint $table) {
        $table->dropForeign(['company_id']);
         $table->dropColumn('company_id');
    });

    // Remove foreign key from social_securities table
    Schema::table('social_securities', function (Blueprint $table) {
        $table->dropForeign(['company_id']);
         $table->dropColumn('company_id');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
