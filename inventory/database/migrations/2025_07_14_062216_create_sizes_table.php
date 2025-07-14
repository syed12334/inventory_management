<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sizes', function (Blueprint $table) {
            $table->id('s_id');
            $table->uuid('size_uuid')->unique()->default(DB::raw('UUID()'));
            $table->string('sname', 90);
            $table->tinyInteger('status')->default(0)->comment('1 - Active, 0 - Deactive');
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('updated_by')->nullable(); // optional: tracks who updated it
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sizes');
    }
};
