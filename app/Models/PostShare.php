<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostShare extends Model
{
    public const STATUS = [
        'pendente'   => 'Pendente',
        'enviado'    => 'Enviado',
        'erro'       => 'Erro',
        'desativado' => 'Integração desativada',
    ];

    protected $fillable = ['post_id', 'rede', 'status', 'resposta', 'url_externa', 'user_id'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
