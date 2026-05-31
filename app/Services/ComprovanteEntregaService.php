<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ComprovanteEntregaService
{
    public function armazenar(?UploadedFile $arquivo): ?string
    {
        if (! $arquivo) {
            return null;
        }

        $destino = public_path('canhotos');

        if (! is_dir($destino)) {
            mkdir($destino, 0755, true);
        }

        $nomeArquivo = now()->format('YmdHis').'_'.Str::random(12).'.'.$arquivo->getClientOriginalExtension();
        $arquivo->move($destino, $nomeArquivo);

        return $nomeArquivo;
    }
}
