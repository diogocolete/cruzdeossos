<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('integrante_id')->constrained('integrantes')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('titulo');
            $table->string('slug')->unique();
            $table->longText('conteudo')->nullable();
            $table->string('imagem')->nullable();
            $table->string('video')->nullable();
            $table->string('video_url')->nullable();
            $table->boolean('destaque')->default(false);
            $table->boolean('publicado')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->json('redes_sociais')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
