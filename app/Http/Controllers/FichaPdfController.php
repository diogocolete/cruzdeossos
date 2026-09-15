<?php

namespace App\Http\Controllers;

use App\Filament\Resources\FichaResource;
use App\Models\Ficha;
use App\Models\FichaRevision;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class FichaPdfController extends Controller
{
    public function atual(Ficha $ficha)
    {
        abort_unless(FichaResource::podeVerFicha($ficha), 403);

        return $this->pdf($ficha, $ficha->revisao);
    }

    public function revisao(FichaRevision $revisao)
    {
        abort_unless(FichaResource::podeVerFicha($revisao->ficha), 403);

        $ficha = $revisao->fichaSnapshot();

        return $this->pdf($ficha, $revisao->revisao, $revisao->created_at);
    }

    protected function pdf(Ficha $ficha, int $revisao, $dataRevisao = null)
    {
        $foto = $this->caminhoFoto($ficha);
        $watermark = $this->caminhoWatermark();

        $pdf = Pdf::loadView('pdf.ficha', [
            'ficha'       => $ficha,
            'revisao'     => $revisao,
            'dataRevisao' => $dataRevisao ?? $ficha->updated_at,
            'foto'        => $foto,
            'watermark'   => $watermark,
        ])->setPaper('a4');

        $apelido = str($ficha->integrante?->apelido ?? 'integrante')->slug();

        // stream = exibe inline no navegador (modal/iframe), sem forçar download
        return $pdf->stream("ficha-{$apelido}-rev{$revisao}.pdf");
    }

    protected function caminhoFoto(Ficha $ficha): ?string
    {
        $foto = $ficha->integrante?->foto;
        if (! $foto) {
            return null;
        }

        $path = storage_path('app/public/' . $foto);

        return is_file($path) ? $path : null;
    }

    /**
     * Gera (uma vez) uma versão com baixa opacidade do patch para marca d'água.
     */
    protected function caminhoWatermark(): ?string
    {
        $cache = storage_path('app/ficha/watermark.png');
        if (is_file($cache)) {
            return $cache;
        }

        $src = public_path('assets/vetor-patch-03.png');
        if (! is_file($src) || ! function_exists('imagecreatefrompng')) {
            return null;
        }

        $img = imagecreatefrompng($src);
        $w = imagesx($img);
        $h = imagesy($img);

        // Reduz para acelerar o ajuste de opacidade pixel a pixel
        $maxW = 700;
        if ($w > $maxW) {
            $nh = (int) round($h * $maxW / $w);
            $tmp = imagecreatetruecolor($maxW, $nh);
            imagealphablending($tmp, false);
            imagesavealpha($tmp, true);
            imagefill($tmp, 0, 0, imagecolorallocatealpha($tmp, 0, 0, 0, 127));
            imagecopyresampled($tmp, $img, 0, 0, 0, 0, $maxW, $nh, $w, $h);
            imagedestroy($img);
            $img = $tmp;
            $w = $maxW;
            $h = $nh;
        }

        $out = imagecreatetruecolor($w, $h);
        imagealphablending($out, false);
        imagesavealpha($out, true);
        imagefill($out, 0, 0, imagecolorallocatealpha($out, 0, 0, 0, 127));

        // Copia pixel a pixel reduzindo o alpha para ~10%
        for ($y = 0; $y < $h; $y++) {
            for ($x = 0; $x < $w; $x++) {
                $rgba = imagecolorat($img, $x, $y);
                $a = ($rgba >> 24) & 0x7F;
                if ($a === 127) {
                    continue;
                }
                $novoAlpha = min(127, 127 - (int) round((127 - $a) * 0.10));
                $cor = imagecolorallocatealpha(
                    $out,
                    ($rgba >> 16) & 0xFF,
                    ($rgba >> 8) & 0xFF,
                    $rgba & 0xFF,
                    $novoAlpha
                );
                imagesetpixel($out, $x, $y, $cor);
            }
        }

        if (! is_dir(dirname($cache))) {
            mkdir(dirname($cache), 0755, true);
        }
        imagepng($out, $cache);
        imagedestroy($img);
        imagedestroy($out);

        return $cache;
    }
}
