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
        Schema::table('subcategory_logs', function (Blueprint $table) {
            $table->integer('from_status')->after('user_id')->comment('1-Active, 0- Inactive, 2- Deleted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subcategory_logs', function (Blueprint $table) {
            //
        });
    }
};
