<?php

namespace Tests\Feature;

use App\Models\Ficha;
use App\Models\FichaRevision;
use App\Models\Integrante;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPagesTest extends TestCase
{
    private function admin(): User
    {
        return User::where('email', 'admin@cruzdeossos.com.br')->firstOrFail();
    }

    public function test_admin_ve_listas(): void
    {
        $admin = $this->admin();

        foreach (['/admin/posts', '/admin/fichas', '/admin/meu-perfil', '/admin/configuracoes'] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }

    public function test_admin_ve_paginas_de_edicao(): void
    {
        $admin = $this->admin();
        $post = Post::firstOrFail();
        $ficha = Ficha::firstOrFail();
        $revisao = $ficha->revisions()->firstOrFail();

        foreach ([
            "/admin/posts/{$post->id}/edit",
            "/admin/posts/create",
            "/admin/fichas/{$ficha->id}",
            "/admin/fichas/{$ficha->id}/edit",
        ] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }

        // PDF de uma revisão antiga
        $this->actingAs($admin)
            ->get(route('admin.ficha.revisao.pdf', $revisao))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_paginas_publicas_de_post_e_integrante(): void
    {
        $post = Post::firstOrFail();

        $this->get(route('posts.index'))->assertOk();
        $this->get(route('posts.show', $post->slug))->assertOk();
        $this->get(route('integrantes.show', $post->integrante->slug))->assertOk();
        // post show expõe link para a página do membro
        $this->get(route('posts.show', $post->slug))
            ->assertSee(route('integrantes.show', $post->integrante->slug), false);
        // página do membro nunca expõe dados da ficha
        $this->get(route('integrantes.show', $post->integrante->slug))
            ->assertDontSee('tipo_sanguineo')
            ->assertDontSee('endereco');
    }

    public function test_pdf_ficha_exige_login(): void
    {
        $ficha = Ficha::firstOrFail();
        $this->get(route('admin.ficha.pdf', $ficha))->assertRedirect();
    }

    public function test_diretoria_baixa_pdf_ficha(): void
    {
        $ficha = Ficha::firstOrFail();
        $this->actingAs($this->admin())
            ->get(route('admin.ficha.pdf', $ficha))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_membro_nao_ve_ficha_de_outro(): void
    {
        $ficha = Ficha::firstOrFail();
        $thiago = Integrante::where('apelido', 'Thiago')->firstOrFail();

        $user = User::firstOrCreate(
            ['email' => 'thiago@teste.local'],
            ['name' => 'Thiago', 'password' => bcrypt('x'), 'integrante_id' => $thiago->id]
        );
        $user->assignRole('Membro');

        // Listagem de fichas exclui a ficha alheia
        $query = \App\Filament\Resources\FichaResource::getEloquentQuery();
        $this->actingAs($user);
        $query = \App\Filament\Resources\FichaResource::getEloquentQuery();
        $this->assertFalse($query->where('id', $ficha->id)->exists());

        // PDF alheio bloqueado
        $this->get(route('admin.ficha.pdf', $ficha))->assertForbidden();

        $user->delete();
    }

    public function test_revisao_nao_sobrescreve(): void
    {
        $ficha = Ficha::firstOrFail();
        $antes = $ficha->revisions()->count();

        $ficha->update(['remedios' => 'Teste de revisão']);
        $ficha->revisao = $ficha->revisao + 1;
        $ficha->saveQuietly();
        $ficha->registrarRevisao(null, 'teste');

        $this->assertEquals($antes + 1, $ficha->revisions()->count());
        $this->assertEquals(1, $ficha->revisions()->min('revisao'));
        // A revisão 1 preserva o valor antigo (não foi sobrescrita)
        $rev1 = $ficha->revisions()->where('revisao', 1)->first();
        $this->assertEquals('Não', $rev1->dados['remedios']);
    }
}
