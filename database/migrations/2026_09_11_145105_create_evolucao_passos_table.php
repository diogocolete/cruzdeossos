<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evolucao_passos', function (Blueprint $table) {
            $table->id();
            $table->integer('numero');
            $table->string('tag');
            $table->string('titulo');
            $table->text('descricao');
            $table->string('imagem')->nullable();
            $table->boolean('destacado')->default(false);
            $table->boolean('publicado')->default(true);
            $table->integer('ordem')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evolucao_passos');
    }
};
