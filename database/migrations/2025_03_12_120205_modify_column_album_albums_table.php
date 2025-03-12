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
        //
        if (Schema::hasTable('albums')) {
            // The table exists...
            Schema::table('albums', function (Blueprint $table) {
                $table->after('user_ulid', function (Blueprint $table) {
                    $table->string('url_path')->nullable();
                    $table->string('dir_path')->nullable();
                });
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
