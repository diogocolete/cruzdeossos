<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Inscricao extends Model
{
    use HasFactory;

    protected $table = 'inscricoes';

    public const STATUS = [
        'analise_basico'   => 'Em Análise - Cadastro Básico',
        'analise_completo' => 'Em Análise - Cadastro Completo',
        'aprovado'         => 'Aprovado',
        'inapto'           => 'Inapto',
    ];

    protected $fillable = [
        'nome_completo', 'rede_social', 'email', 'telefone',
        'whatsapp', 'endereco', 'cpf', 'rg', 'data_nascimento',
        'moto', 'ja_pertenceu_clube', 'clube_anterior',
        'status', 'notas', 'token',
    ];

    protected $casts = [
        'data_nascimento'    => 'date',
        'ja_pertenceu_clube' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Inscricao $inscricao) {
            $inscricao->token ??= Str::random(48);
        });
    }

    public function cadastroCompleto(): bool
    {
        return $this->status !== 'analise_basico';
    }

    public function scopeRecentes($query)
    {
        return $query->orderByDesc('created_at');
    }
}
