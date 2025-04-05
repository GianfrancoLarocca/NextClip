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
        Schema::create('channels', function (Blueprint $table) {
            $table->id();

            // Relazione: ogni canale appartiene a un utente
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Informazioni del canale
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('avatar')->nullable();
            $table->string('banner')->nullable();

            // Slug per URL SEO-friendly
            $table->string('slug')->unique();

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};
