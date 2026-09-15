<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fichas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('integrante_id')->unique()->constrained('integrantes')->onDelete('cascade');
            $table->string('nome_completo');
            $table->date('data_nascimento')->nullable();
            $table->string('apelido')->nullable();
            $table->string('cargo')->nullable();
            $table->string('tipo_sanguineo', 3)->nullable();
            $table->text('alergias')->nullable();
            $table->text('remedios')->nullable();
            $table->text('doencas')->nullable();
            $table->text('cirurgias')->nullable();
            $table->string('endereco_logradouro')->nullable();
            $table->string('endereco_bairro')->nullable();
            $table->string('endereco_cidade')->nullable();
            $table->string('endereco_uf', 2)->nullable();
            $table->string('endereco_cep', 10)->nullable();
            $table->json('contatos')->nullable();
            $table->json('anexos')->nullable();
            $table->unsignedInteger('revisao')->default(1);
            $table->timestamps();
        });

        Schema::create('ficha_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ficha_id')->constrained('fichas')->onDelete('cascade');
            $table->unsignedInteger('revisao');
            $table->json('dados');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('resumo')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['ficha_id', 'revisao']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ficha_revisions');
        Schema::dropIfExists('fichas');
    }
};
