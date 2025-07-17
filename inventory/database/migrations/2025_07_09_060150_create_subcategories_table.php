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
        Schema::create('subcategories', function (Blueprint $table) {
            $table->bigIncrements('subcategory_id'); // PRIMARY KEY
            $table->char('subcategory_uuid', 36);
            $table->unsignedBigInteger('category_id');
            $table->string('subcategory_name', 255);
            $table->tinyInteger('status')->default(1);
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps(); // created_at & updated_at

            // ✅ Foreign key constraints
            $table->foreign('category_id')->references('category_id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subcategories');
    }
};
