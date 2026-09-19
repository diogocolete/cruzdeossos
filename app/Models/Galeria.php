<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeria extends Model
{
    use HasFactory;

    protected $table = 'galeria';

    protected $fillable = ['titulo', 'imagem', 'descricao', 'ordem', 'publicado', 'destaque'];

    protected $casts = ['publicado' => 'boolean', 'destaque' => 'boolean'];

    public function scopePublicados($query)
    {
        return $query->where('publicado', true)->orderBy('ordem');
    }

    public function scopeDestaques($query)
    {
        return $query->publicados()->where('destaque', true);
    }
}
