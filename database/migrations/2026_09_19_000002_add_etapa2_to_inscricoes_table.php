<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inscricoes', function (Blueprint $table) {
            $table->string('token', 64)->nullable()->unique()->after('id');
            $table->string('cpf', 14)->nullable()->after('endereco');
            $table->string('rg', 20)->nullable()->after('cpf');
            $table->date('data_nascimento')->nullable()->after('rg');
            $table->string('moto', 150)->nullable()->after('data_nascimento');
            $table->boolean('ja_pertenceu_clube')->nullable()->after('moto');
            $table->string('clube_anterior', 150)->nullable()->after('ja_pertenceu_clube');
        });
    }

    public function down(): void
    {
        Schema::table('inscricoes', function (Blueprint $table) {
            $table->dropColumn([
                'token', 'cpf', 'rg', 'data_nascimento',
                'moto', 'ja_pertenceu_clube', 'clube_anterior',
            ]);
        });
    }
};
