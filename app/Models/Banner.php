<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banners';

    protected $fillable = [
        'kicker', 'titulo', 'subtitulo', 'texto', 'imagem',
        'btn1_texto', 'btn1_link', 'btn2_texto', 'btn2_link',
        'publicado', 'ordem',
    ];

    protected $casts = ['publicado' => 'boolean'];

    public function scopePublicados($query)
    {
        return $query->where('publicado', true)->orderBy('ordem');
    }
}
