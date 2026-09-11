<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeria extends Model
{
    use HasFactory;

    protected $table = 'galeria';

    protected $fillable = ['titulo', 'imagem', 'descricao', 'ordem', 'publicado'];

    protected $casts = ['publicado' => 'boolean'];

    public function scopePublicados($query)
    {
        return $query->where('publicado', true)->orderBy('ordem');
    }
}
