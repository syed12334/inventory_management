<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'category_uuid')) {
                $table->uuid('category_uuid')->after('category_id')->unique();
            }

            if (!Schema::hasColumn('categories', 'user_id')) {
                $table->unsignedBigInteger('user_id')->after('category_uuid');
            }
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'user_id')) {
                $table->dropForeign(['user_id']); // only if a foreign key exists
                $table->dropColumn('user_id');
            }

            if (Schema::hasColumn('categories', 'category_uuid')) {
                $table->dropColumn('category_uuid');
            }
        });
    }
};
