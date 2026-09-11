<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvolucaoPasso extends Model
{
    use HasFactory;

    protected $table = 'evolucao_passos';

    protected $fillable = [
        'numero', 'tag', 'titulo', 'descricao', 'imagem',
        'destacado', 'publicado', 'ordem',
    ];

    protected $casts = [
        'destacado' => 'boolean',
        'publicado' => 'boolean',
    ];

    public function scopePublicados($query)
    {
        return $query->where('publicado', true)->orderBy('ordem');
    }
}
