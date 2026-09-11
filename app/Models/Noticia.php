<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Noticia extends Model
{
    use HasFactory;

    protected $table = 'noticias';

    protected $fillable = ['titulo', 'slug', 'resumo', 'conteudo', 'imagem', 'user_id', 'data_publicacao', 'publicado'];

    protected $casts = ['data_publicacao' => 'date', 'publicado' => 'boolean'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublicados($query)
    {
        return $query->where('publicado', true)->orderByDesc('data_publicacao');
    }
}
