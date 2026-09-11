<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Conteudo extends Model
{
    protected $table = 'conteudos';

    protected $fillable = ['chave', 'valor'];

    public $timestamps = true;

    public static function get(string $chave, mixed $default = null): mixed
    {
        return Cache::rememberForever("conteudo:{$chave}", function () use ($chave, $default) {
            $c = static::where('chave', $chave)->first();
            return $c?->valor ?? $default;
        });
    }

    public static function set(string $chave, mixed $valor): void
    {
        static::updateOrCreate(
            ['chave' => $chave],
            ['valor' => $valor]
        );
        Cache::forget("conteudo:{$chave}");
    }

    public static function muitos(array $chaves): array
    {
        $resultado = [];
        foreach ($chaves as $chave => $default) {
            $resultado[$chave] = self::get($chave, $default);
        }
        return $resultado;
    }
}
