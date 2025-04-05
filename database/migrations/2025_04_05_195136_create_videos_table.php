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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();

            // Relazione con il canale
            $table->foreignId('channel_id')->constrained()->onDelete('cascade');

            $table->string('title');
            $table->text('description')->nullable();

            // File del video e thumbnail
            $table->string('video_path');
            $table->string('thumbnail_path')->nullable();

            // SEO e visualizzazione
            $table->string('slug')->unique();
            $table->enum('visibility', ['public', 'private', 'unlisted'])->default('public');

            $table->unsignedBigInteger('views')->default(0);
            $table->integer('duration')->nullable(); // secondi

            $table->timestamp('published_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
