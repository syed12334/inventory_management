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
        Schema::create('subcategory_logs', function (Blueprint $table) {
            $table->id(); // id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->unsignedBigInteger('subcategory_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('from_status')->comment('1-Active, 0-Inactive, 2-Deleted');
            $table->tinyInteger('type')->comment('1-Inserted, 2-Updated, 3-Deleted, 4-Status Change');
            $table->timestamps(); // created_at & updated_at

            // Optional foreign key constraints
            $table->foreign('subcategory_id')->references('subcategory_id')->on('subcategories')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subcategory_logs');
    }
};
