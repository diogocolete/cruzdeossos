<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recurso extends Model
{
    use HasFactory;

    protected $table = 'recursos';

    protected $fillable = ['icone', 'titulo', 'descricao', 'publicado', 'ordem'];

    protected $casts = ['publicado' => 'boolean'];

    public function scopePublicados($query)
    {
        return $query->where('publicado', true)->orderBy('ordem');
    }
}
