<?php

namespace App\Services;

use App\Models\Configuracao;
use App\Models\Post;
use App\Models\PostShare;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Publica posts nas redes sociais configuradas.
 *
 * Controle: cada rede só é acionada se estiver ativa E configurada em
 * Configurações → Integrações. Caso contrário o compartilhamento fica
 * marcado como "desativado" no log (post_shares).
 */
class SocialPublisher
{
    /**
     * Processa todos os compartilhamentos pendentes de um post.
     */
    public function publicarPendentes(Post $post): void
    {
        // Rascunhos não são compartilhados — ficam pendentes até publicar
        if (! $post->publicado) {
            return;
        }

        $post->shares()->where('status', 'pendente')->get()
            ->each(fn (PostShare $share) => $this->publicar($share));
    }

    public function publicar(PostShare $share): void
    {
        $post = $share->post;

        if (! $this->integracaoAtiva($share->rede)) {
            $share->update([
                'status'   => 'desativado',
                'resposta' => 'Integração desativada ou não configurada. Ative em Configurações → Integrações.',
            ]);
            return;
        }

        try {
            $resultado = match ($share->rede) {
                'facebook'  => $this->publicarFacebook($post),
                'instagram' => $this->publicarInstagram($post),
                default     => throw new \RuntimeException("Rede '{$share->rede}' não suportada."),
            };

            $share->update([
                'status'      => 'enviado',
                'resposta'    => $resultado['resposta'] ?? null,
                'url_externa' => $resultado['url'] ?? null,
            ]);
        } catch (\Throwable $e) {
            Log::warning("Falha ao compartilhar post {$post->id} em {$share->rede}: {$e->getMessage()}");
            $share->update(['status' => 'erro', 'resposta' => $e->getMessage()]);
        }
    }

    public function integracaoAtiva(string $rede): bool
    {
        return match ($rede) {
            'facebook'  => Configuracao::get('int_facebook_ativo', '0') === '1'
                && filled(Configuracao::get('int_facebook_page_id'))
                && filled(Configuracao::get('int_facebook_token')),
            'instagram' => Configuracao::get('int_instagram_ativo', '0') === '1'
                && filled(Configuracao::get('int_instagram_account_id'))
                && filled(Configuracao::get('int_instagram_token')),
            default     => false,
        };
    }

    /**
     * Facebook Graph API: publica no feed da página (com imagem quando houver).
     */
    protected function publicarFacebook(Post $post): array
    {
        $pageId = Configuracao::get('int_facebook_page_id');
        $token  = Configuracao::get('int_facebook_token');
        $link   = route('posts.show', $post->slug);

        $mensagem = $post->titulo . "\n\n" . $link;

        if ($post->imagem) {
            $response = Http::post("https://graph.facebook.com/v19.0/{$pageId}/photos", [
                'url'          => asset('storage/' . $post->imagem),
                'caption'      => $mensagem,
                'access_token' => $token,
            ]);
        } else {
            $response = Http::post("https://graph.facebook.com/v19.0/{$pageId}/feed", [
                'message'      => $mensagem,
                'link'         => $link,
                'access_token' => $token,
            ]);
        }

        if ($response->failed()) {
            throw new \RuntimeException('Facebook: ' . $response->body());
        }

        return ['resposta' => $response->body(), 'url' => null];
    }

    /**
     * Instagram Graph API: cria container de mídia e publica.
     * Requer imagem pública (imagem de capa do post).
     */
    protected function publicarInstagram(Post $post): array
    {
        if (! $post->imagem) {
            throw new \RuntimeException('Instagram exige imagem de capa no post.');
        }

        $accountId = Configuracao::get('int_instagram_account_id');
        $token     = Configuracao::get('int_instagram_token');
        $caption   = $post->titulo . "\n\n" . route('posts.show', $post->slug);

        $container = Http::post("https://graph.facebook.com/v19.0/{$accountId}/media", [
            'image_url'    => asset('storage/' . $post->imagem),
            'caption'      => $caption,
            'access_token' => $token,
        ]);

        if ($container->failed() || ! $container->json('id')) {
            throw new \RuntimeException('Instagram (container): ' . $container->body());
        }

        $publish = Http::post("https://graph.facebook.com/v19.0/{$accountId}/media_publish", [
            'creation_id'  => $container->json('id'),
            'access_token' => $token,
        ]);

        if ($publish->failed()) {
            throw new \RuntimeException('Instagram (publish): ' . $publish->body());
        }

        return ['resposta' => $publish->body(), 'url' => null];
    }
}
