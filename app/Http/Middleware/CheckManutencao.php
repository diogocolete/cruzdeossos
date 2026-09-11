<?php

namespace App\Http\Middleware;

use App\Models\Configuracao;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckManutencao
{
    /**
     * Se o site está em manutenção e o usuário NÃO está logado no painel
     * admin, exibe a página de manutenção. Usuários autenticados no Filament
     * passam normalmente.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Configuracao::emManutencao()) {
            return $next($request);
        }

        // Libera quem está autenticado (admin logado no Filament)
        if (auth()->check()) {
            return $next($request);
        }

        // Libera rotas do próprio painel admin (login, assets, etc.)
        if ($request->is('admin*') || $request->is('livewire*') || $request->is('login*')) {
            return $next($request);
        }

        return response()->view('site.manutencao', [], 503);
    }
}
