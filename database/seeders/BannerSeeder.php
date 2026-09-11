<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'kicker' => 'Cavaleiros da estrada',
                'titulo' => 'Ande & Viva',
                'subtitulo' => 'Hoje rodamos juntos',
                'texto' => 'Irmãos unidos pelo asfalto, pela liberdade e pela cruz de ossos. Mais do que um clube, uma irmandade.',
                'imagem' => 'banners/banner-3-esboco.png',
                'btn1_texto' => 'Conheça o Clube',
                'btn1_link' => '#about',
                'btn2_texto' => 'Explore nossos passeios',
                'btn2_link' => '#join',
                'publicado' => true,
                'ordem' => 1,
            ],
            [
                'kicker' => 'Irmandade desde 22/03/2025',
                'titulo' => 'Cruz de Ossos',
                'subtitulo' => 'Irmandade',
                'texto' => 'Rode conosco numa jornada pelo desconhecido. Explore novas fronteiras e acorde com a emoção de estar onde nunca esteve antes.',
                'imagem' => 'banners/banner-5-esboco.png',
                'btn1_texto' => 'Nossa História',
                'btn1_link' => '#events',
                'btn2_texto' => 'Ver Galeria',
                'btn2_link' => '#gallery',
                'publicado' => true,
                'ordem' => 2,
            ],
            [
                'kicker' => 'Irmandade desde 22/03/2025',
                'titulo' => 'Irmãos do Asfalto',
                'subtitulo' => 'A família só cresce',
                'texto' => 'Nossa irmandade incentiva a participação da família num ambiente divertido, seguindo rigorosas diretrizes de segurança.',
                'imagem' => 'banners/banner-2-esboco.png',
                'btn1_texto' => 'Junte-se a Nós',
                'btn1_link' => '#join',
                'btn2_texto' => 'Por Que Nós',
                'btn2_link' => '#why',
                'publicado' => true,
                'ordem' => 3,
            ],
        ];

        foreach ($banners as $data) {
            Banner::firstOrCreate(
                ['titulo' => $data['titulo']],
                $data
            );
        }
    }
}
