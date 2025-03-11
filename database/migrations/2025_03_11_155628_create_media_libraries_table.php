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
        Schema::create('media_libraries', function (Blueprint $table) {
            $table->ulid('id')->unique();
            $table->foreignUlid('user_ulid')->nullable();
            $table->foreignUlid('album_id')->constrained()->onDelete('cascade');
            $table->string('url_path')->nullable();
            $table->string('dir_path')->nullable();
            $table->string('name')->unique();
            $table->tinyText('intro')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_libraries');
    }
};
