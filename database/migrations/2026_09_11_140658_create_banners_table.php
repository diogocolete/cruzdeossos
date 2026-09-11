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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('kicker')->nullable();
            $table->string('titulo');
            $table->string('subtitulo')->nullable();
            $table->text('texto')->nullable();
            $table->string('imagem');
            $table->string('btn1_texto')->nullable();
            $table->string('btn1_link')->nullable();
            $table->string('btn2_texto')->nullable();
            $table->string('btn2_link')->nullable();
            $table->boolean('publicado')->default(true);
            $table->integer('ordem')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
