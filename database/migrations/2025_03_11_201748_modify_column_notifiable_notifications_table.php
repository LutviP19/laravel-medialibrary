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
        if (Schema::hasTable('notifications')) {
            // The "notifications" table exists...
            Schema::table('notifications', function (Blueprint $table) {
                $table->after('notifiable_type', function (Blueprint $table) {
                    $table->ulid('notifiable_id')->change();
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
