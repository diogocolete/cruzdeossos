<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ficha extends Model
{
    use HasFactory;

    public const TIPOS_SANGUINEOS = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

    public const CAMPOS_SNAPSHOT = [
        'nome_completo', 'data_nascimento', 'apelido', 'cargo', 'tipo_sanguineo',
        'alergias', 'remedios', 'doencas', 'cirurgias',
        'endereco_logradouro', 'endereco_bairro', 'endereco_cidade',
        'endereco_uf', 'endereco_cep',
        'contatos', 'anexos',
    ];

    protected $fillable = [
        'integrante_id', 'nome_completo', 'data_nascimento', 'apelido', 'cargo',
        'tipo_sanguineo', 'alergias', 'remedios', 'doencas', 'cirurgias',
        'endereco_logradouro', 'endereco_bairro', 'endereco_cidade',
        'endereco_uf', 'endereco_cep', 'contatos', 'anexos', 'revisao',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'contatos'        => 'array',
        'anexos'          => 'array',
    ];

    public function integrante(): BelongsTo
    {
        return $this->belongsTo(Integrante::class);
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(FichaRevision::class)->orderByDesc('revisao');
    }

    /**
     * Snapshot dos dados atuais da ficha.
     */
    public function dadosSnapshot(): array
    {
        $dados = $this->only(self::CAMPOS_SNAPSHOT);
        $dados['data_nascimento'] = $this->data_nascimento?->format('Y-m-d');

        return $dados;
    }

    /**
     * Grava uma nova revisão com o estado atual da ficha.
     */
    public function registrarRevisao(?int $userId = null, ?string $resumo = null): FichaRevision
    {
        return $this->revisions()->create([
            'revisao' => $this->revisao,
            'dados'   => $this->dadosSnapshot(),
            'user_id' => $userId,
            'resumo'  => $resumo,
        ]);
    }

    /**
     * Linha de endereço formatada como na ficha em PDF.
     */
    public function enderecoCompleto(): string
    {
        return collect([
            $this->endereco_logradouro,
            $this->endereco_bairro,
            $this->endereco_cidade,
            $this->endereco_uf,
            $this->endereco_cep ? 'Cep: ' . $this->endereco_cep : null,
        ])->filter()->implode(' - ');
    }
}
