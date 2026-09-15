<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    public const REDES_DISPONIVEIS = [
        'facebook'  => 'Facebook',
        'instagram' => 'Instagram',
    ];

    protected $fillable = [
        'integrante_id', 'user_id', 'titulo', 'slug', 'conteudo',
        'imagem', 'video', 'video_url',
        'destaque', 'publicado', 'published_at', 'redes_sociais',
    ];

    protected $casts = [
        'destaque'      => 'boolean',
        'publicado'     => 'boolean',
        'published_at'  => 'datetime',
        'redes_sociais' => 'array',
    ];

    public function integrante(): BelongsTo
    {
        return $this->belongsTo(Integrante::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shares(): HasMany
    {
        return $this->hasMany(PostShare::class);
    }

    public function scopePublicados($query)
    {
        return $query->where('publicado', true)
            ->orderByDesc('published_at')
            ->orderByDesc('created_at');
    }

    /**
     * Posts para a home: até 6, destaques primeiro, completado com os mais recentes.
     */
    public static function paraHome(int $limite = 6)
    {
        $destaques = static::publicados()->where('destaque', true)->limit($limite)->get();

        if ($destaques->count() < $limite) {
            $restante = static::publicados()
                ->where('destaque', false)
                ->limit($limite - $destaques->count())
                ->get();
            $destaques = $destaques->concat($restante);
        }

        return $destaques;
    }

    /**
     * Cria registros de compartilhamento pendentes para as redes selecionadas.
     * Só cria para redes ainda não registradas (evita duplicar em edições).
     */
    public function registrarCompartilhamentos(?int $userId = null): void
    {
        foreach ($this->redes_sociais ?? [] as $rede) {
            $this->shares()->firstOrCreate(
                ['rede' => $rede],
                ['status' => 'pendente', 'user_id' => $userId]
            );
        }
    }

    /**
     * Extrai o ID de um vídeo do YouTube a partir da URL informada.
     */
    public function youtubeEmbedUrl(): ?string
    {
        if (empty($this->video_url)) {
            return null;
        }

        if (preg_match('~(?:youtube\.com/(?:watch\?v=|embed/|shorts/)|youtu\.be/)([\w-]{6,})~', $this->video_url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }

        if (preg_match('~vimeo\.com/(\d+)~', $this->video_url, $m)) {
            return 'https://player.vimeo.com/video/' . $m[1];
        }

        return null;
    }
}
