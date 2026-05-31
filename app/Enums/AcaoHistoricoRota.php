<?php

namespace App\Enums;

use Illuminate\Support\Str;
use InvalidArgumentException;

enum AcaoHistoricoRota: string
{
    case EM_TRANSITO = 'Em trânsito';
    case FINALIZADO = 'Finalizado';
    case OCORRENCIA = 'Ocorrência';

    public static function fromRequest(?string $status): self
    {
        $normalizado = self::normalizar($status ?? '');

        return match ($normalizado) {
            'emtransito' => self::EM_TRANSITO,
            'finalizado' => self::FINALIZADO,
            'ocorrencia' => self::OCORRENCIA,
            default => throw new InvalidArgumentException('Status de movimentação inválido.'),
        };
    }

    private static function normalizar(string $valor): string
    {
        return str_replace([' ', '_', '-'], '', Str::lower(Str::ascii(trim($valor))));
    }
}
