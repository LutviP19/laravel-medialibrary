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
        if (Schema::hasTable('media')) {
            // The "notifications" table exists...
            Schema::table('media', function (Blueprint $table) {
                $table->after('model_type', function (Blueprint $table) {
                    $table->ulid('model_id')->change();
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
