<?php

namespace App\Enums;

use Illuminate\Support\Str;
use InvalidArgumentException;

enum TipoRota: string
{
    case COLETA = 'Coleta';
    case TRANSFERENCIA = 'Transferencia';
    case ENTREGA = 'Entrega';

    public static function fromRequest(?string $tipo): self
    {
        $normalizado = self::normalizar($tipo ?? '');

        return match ($normalizado) {
            'coleta' => self::COLETA,
            'transferencia' => self::TRANSFERENCIA,
            'entrega' => self::ENTREGA,
            default => throw new InvalidArgumentException('Tipo de rota inválido.'),
        };
    }

    private static function normalizar(string $valor): string
    {
        return str_replace([' ', '_', '-'], '', Str::lower(Str::ascii(trim($valor))));
    }
}
