<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeria extends Model
{
    use HasFactory;

    protected $table = 'galeria';

    protected $fillable = ['titulo', 'imagem', 'descricao', 'ordem', 'publicado', 'destaque', 'pasta'];

    protected $casts = ['publicado' => 'boolean', 'destaque' => 'boolean'];

    public function scopePublicados($query)
    {
        return $query->where('publicado', true)->orderBy('ordem');
    }

    public function scopeDestaques($query)
    {
        return $query->publicados()->where('destaque', true);
    }

    public function scopeDaPasta($query, string $pasta)
    {
        return $query->where('pasta', $pasta);
    }

    public static function pastas(bool $apenasPublicados = false): array
    {
        $query = static::query()
            ->whereNotNull('pasta')
            ->where('pasta', '!=', '');

        if ($apenasPublicados) {
            $query->where('publicado', true);
        }

        return $query->distinct()->orderBy('pasta')->pluck('pasta', 'pasta')->toArray();
    }
}
