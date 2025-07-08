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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->string('author')->nullable();
            $table->string('source_id');
            $table->text('url')->unique();
            $table->string('url_to_image')->nullable();
            $table->string('category')->nullable();
            $table->timestamp('published_at');
            $table->timestamps();

            $table->index('published_at');
            $table->index('category');
            $table->index('source_id');
            $table->index('author');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
