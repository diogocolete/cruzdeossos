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

    public function test_formulario_junte_se(): void
    {
        // página dedicada
        $this->get('/junte-se')->assertOk();

        $this->post(route('junte-se.store'), [
            'nome_completo' => 'Candidato Teste',
            'rede_social' => '@candidato',
            'email' => 'cand@teste.local',
            'telefone' => '41 99999-0000',
            'whatsapp' => '41 99999-0000',
            'endereco' => 'Curitiba',
        ])->assertRedirect();

        $this->assertDatabaseHas('inscricoes', ['nome_completo' => 'Candidato Teste', 'status' => 'novo']);

        // honeypot: preenchido = bot, finge sucesso sem salvar
        $antes = \App\Models\Inscricao::count();
        $this->post(route('junte-se.store'), [
            'nome_completo' => 'Bot',
            'website' => 'http://spam',
        ]);
        $this->assertEquals($antes, \App\Models\Inscricao::count());
    }

    public function test_usuario_inativo_nao_acessa_painel(): void
    {
        $ficha = Ficha::firstOrFail();
        $thiago = Integrante::where('apelido', 'Thiago')->firstOrFail();

        $user = User::create([
            'name' => 'Thiago', 'email' => 'thiago2@teste.local',
            'password' => bcrypt('x'), 'integrante_id' => $thiago->id, 'ativo' => false,
        ]);
        $user->assignRole('Membro');

        $this->assertFalse($user->canAccessPanel(\Filament\Facades\Filament::getPanel('admin')));

        // painel bloqueia mesmo autenticado
        $this->actingAs($user)->get('/admin')->assertForbidden();

        $user->delete();
    }

    public function test_papeis_e_inscricoes_restritos_a_diretoria(): void
    {
        $thiago = Integrante::where('apelido', 'Thiago')->firstOrFail();
        $membro = User::create([
            'name' => 'T', 'email' => 't'.uniqid().'@teste.local',
            'password' => bcrypt('x'), 'integrante_id' => $thiago->id,
        ]);
        $membro->assignRole('Membro');

        // membro comum não acessa gestão de usuários/papéis
        $this->actingAs($membro)->get('/admin/users')->assertForbidden();
        $this->actingAs($membro)->get('/admin/roles')->assertForbidden();
        $status = $this->actingAs($membro)->get('/admin/inscricoes')->getStatusCode();
        $this->assertContains($status, [403, 404]);

        // diretoria acessa
        $admin = $this->admin();
        $this->actingAs($admin)->get('/admin/users')->assertOk();
        $this->actingAs($admin)->get('/admin/roles')->assertOk();
        $this->actingAs($admin)->get('/admin/inscricoes')->assertOk();

        $membro->delete();
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
