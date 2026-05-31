<?php

namespace App\Enums;

use DomainException;

enum StatusHistoricoPedido: string
{
    case AGUARDANDO_COLETA = 'Aguardando coleta';
    case EM_PROCESSO_COLETA = 'Em processo de coleta';
    case COLETA_REALIZADA = 'Coleta realizada';
    case COLETA_NAO_REALIZADA = 'Coleta não realizada';
    case AGUARDANDO_TRANSFERENCIA = 'Aguardando transferência';
    case EM_PROCESSO_TRANSFERENCIA = 'Em processo de transferência';
    case TRANSFERENCIA_REALIZADA = 'Transferência realizada';
    case TRANSFERENCIA_NAO_REALIZADA = 'Transferência não realizada';
    case EM_PROCESSO_SEPARACAO_DESTINO = 'Em processo de separação no destino';
    case EM_ROTA_ENTREGA = 'Em rota de entrega';
    case ENTREGA_REALIZADA = 'Entrega realizada';
    case ENTREGA_NAO_REALIZADA = 'Entrega não realizada';

    public static function fromRouteAction(TipoRota $tipo, AcaoHistoricoRota $acao): self
    {
        return match ($tipo) {
            TipoRota::COLETA => match ($acao) {
                AcaoHistoricoRota::EM_TRANSITO => self::EM_PROCESSO_COLETA,
                AcaoHistoricoRota::FINALIZADO => self::COLETA_REALIZADA,
                AcaoHistoricoRota::OCORRENCIA => self::COLETA_NAO_REALIZADA,
            },
            TipoRota::TRANSFERENCIA => match ($acao) {
                AcaoHistoricoRota::EM_TRANSITO => self::EM_PROCESSO_TRANSFERENCIA,
                AcaoHistoricoRota::FINALIZADO => self::TRANSFERENCIA_REALIZADA,
                AcaoHistoricoRota::OCORRENCIA => self::TRANSFERENCIA_NAO_REALIZADA,
            },
            TipoRota::ENTREGA => match ($acao) {
                AcaoHistoricoRota::EM_TRANSITO => self::EM_ROTA_ENTREGA,
                AcaoHistoricoRota::FINALIZADO => self::ENTREGA_REALIZADA,
                AcaoHistoricoRota::OCORRENCIA => self::ENTREGA_NAO_REALIZADA,
            },
        };
    }

    public static function inicialDaRota(TipoRota $tipo): self
    {
        return match ($tipo) {
            TipoRota::COLETA => self::AGUARDANDO_COLETA,
            TipoRota::TRANSFERENCIA => self::AGUARDANDO_TRANSFERENCIA,
            TipoRota::ENTREGA => self::EM_PROCESSO_SEPARACAO_DESTINO,
        };
    }

    public static function entregaFinal(string $status): self
    {
        $status = self::tryFrom($status);

        if (! $status?->isEntregaFinal()) {
            throw new DomainException('Status de entrega inválido.');
        }

        return $status;
    }

    public function isFinalizadorDeRota(): bool
    {
        return in_array($this, [
            self::COLETA_REALIZADA,
            self::TRANSFERENCIA_REALIZADA,
            self::ENTREGA_REALIZADA,
        ], true);
    }

    public function isEntregaFinal(): bool
    {
        return in_array($this, [
            self::ENTREGA_REALIZADA,
            self::ENTREGA_NAO_REALIZADA,
        ], true);
    }
}
