<?php

namespace App\Http\Controllers;

use App\Enums\TipoRota;
use App\Http\Requests\EntregaHistoricoRequest;
use App\Services\ComprovanteEntregaService;
use App\Services\RotaHistoricoService;
use Carbon\Carbon;
use DomainException;
use Throwable;

class HistoricoController extends Controller
{
    public function store(
        EntregaHistoricoRequest $request,
        ComprovanteEntregaService $comprovantes,
        RotaHistoricoService $historicos,
    ) {
        try {
            $data = $request->validated();

            if (TipoRota::fromRequest($data['tipo']) !== TipoRota::ENTREGA) {
                throw new DomainException('Atualização bloqueada — apenas notas de entrega estão disponíveis para alteração.');
            }

            $foto = $comprovantes->armazenar($request->file('foto'));

            $historicos->registrarEntregaPedido(
                rotaId: (int) $data['rotas_id_rotas'],
                pedidoId: (int) $data['pedido_id_pedido'],
                status: $data['status'],
                data: Carbon::parse($data['data']),
                foto: $foto,
                observacao: $data['observacao'] ?? null,
            );

            return redirect()->route('rotas.show', ['rotas' => $data['rotas_id_rotas']])
                ->with('success', 'Histórico criado com sucesso!');
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        } catch (Throwable $th) {
            report($th);

            return back()->with('error', 'Erro ao salvar histórico: ');
        }
    }
}
