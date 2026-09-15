<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Configuracao extends Model
{
    protected $table = 'configuracoes';

    protected $fillable = ['chave', 'valor'];

    public $timestamps = true;

    /**
     * Retorna o valor de uma configuração (com cache).
     */
    public static function get(string $chave, mixed $default = null): mixed
    {
        return Cache::rememberForever("config:{$chave}", function () use ($chave, $default) {
            $config = static::where('chave', $chave)->first();
            return $config?->valor ?? $default;
        });
    }

    /**
     * Define ou atualiza uma configuração e limpa o cache.
     */
    public static function set(string $chave, mixed $valor): void
    {
        static::updateOrCreate(
            ['chave' => $chave],
            ['valor' => $valor]
        );
        Cache::forget("config:{$chave}");
    }

    /**
     * Verifica se o site está em manutenção.
     */
    public static function emManutencao(): bool
    {
        return self::get('manutencao_ativa', '0') === '1';
    }

    /**
     * Verifica se uma seção do site está ativa.
     */
    public static function secaoAtiva(string $chave): bool
    {
        return self::get($chave, '1') === '1';
    }

    /**
     * Retorna todas as seções com seu estado de ativação.
     */
    public static function secoes(): array
    {
        $secoes = [
            'sec_sobre', 'sec_eventos', 'sec_por_que_nos', 'sec_evolucao',
            'sec_cta', 'sec_galeria', 'sec_integrantes', 'sec_posts', 'sec_noticias',
            'sec_depoimentos', 'sec_junte_se', 'sec_contato',
        ];

        $resultado = [];
        foreach ($secoes as $chave) {
            $resultado[$chave] = self::secaoAtiva($chave);
        }
        return $resultado;
    }
}
