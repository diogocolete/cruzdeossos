<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscricao extends Model
{
    use HasFactory;

    protected $table = 'inscricoes';

    public const STATUS = [
        'novo'       => 'Novo',
        'em_contato' => 'Em contato',
        'aprovado'   => 'Aprovado',
        'arquivado'  => 'Arquivado',
    ];

    protected $fillable = [
        'nome_completo', 'rede_social', 'email', 'telefone',
        'whatsapp', 'endereco', 'status', 'notas',
    ];

    public function scopeRecentes($query)
    {
        return $query->orderByDesc('created_at');
    }
}
