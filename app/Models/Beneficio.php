<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficio extends Model
{
    use HasFactory;

    protected $table = 'beneficios';

    protected $fillable = ['icone', 'titulo', 'descricao', 'publicado', 'ordem'];

    protected $casts = ['publicado' => 'boolean'];

    public function scopePublicados($query)
    {
        return $query->where('publicado', true)->orderBy('ordem');
    }
}
