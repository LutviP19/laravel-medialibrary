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
        if (Schema::hasTable('users')) {
            // The "users" table exists...
            Schema::table('users', function (Blueprint $table) {
                $table->after('id', function (Blueprint $table) {
                    $table->ulid('ulid')->nullable();
                });
                
                $table->after('profile_photo_path', function (Blueprint $table) {
                    $table->string('first_name', length: 100)->nullable();
                    $table->string('last_name', length: 100)->nullable();
                    $table->string('phone', length: 30)->nullable();
                    $table->string('address_line1', length: 150)->nullable();
                    $table->string('address_line2', length: 200)->nullable();
                    $table->string('city', length: 100)->nullable();
                    $table->string('default_url')->nullable();
                    $table->boolean('status')->default(true);
                });

                $table->after('updated_at', function (Blueprint $table) {
                    $table->softDeletes();
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
