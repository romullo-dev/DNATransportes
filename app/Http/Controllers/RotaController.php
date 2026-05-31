<?php

namespace App\Http\Controllers;

use App\DTOs\AdminRotaUpdateData;
use App\DTOs\RegistrarHistoricoRotaData;
use App\DTOs\RotaData;
use App\Http\Requests\AdminUpdateRotaRequest;
use App\Http\Requests\RotaRequest;
use App\Http\Requests\StoreRotaRequest;
use App\Http\Requests\UpdateRotaRequest;
use App\Models\Rota;
use App\Repositories\HistoricoRepository;
use App\Services\ComprovanteEntregaService;
use App\Services\RomaneioPdfService;
use App\Services\RotaHistoricoService;
use App\Services\RotaService;
use DomainException;
use Throwable;

class RotaController extends Controller
{
    public function index(RotaService $rotas)
    {
        $rota = $rotas->listar();

        return View('rotas.index', compact('rota'));
    }

    public function create(RotaService $rotas)
    {
        return View('rotas.create', $rotas->dadosFormulario());
    }

    public function store(StoreRotaRequest $request, RotaService $rotas)
    {
        try {
            $rotas->criar(RotaData::fromArray($request->validated()));

            return redirect()->route('rotas.index')->with('success', 'Rota cadastrada com sucesso!');
        } catch (DomainException $e) {
            return redirect()->route('rotas.index')->with('error', $e->getMessage());
        } catch (Throwable $e) {
            report($e);

            return redirect()->route('rotas.index')->with('error', 'Erro ao cadastrar rota.');
        }
    }

    public function store_entrega(StoreRotaRequest $request, RotaService $rotas)
    {
        try {
            $rotas->criar(RotaData::fromArray($request->validated()));

            return redirect()->route('rotas.index')->with('success', 'Rota de entrega cadastrada com sucesso!');
        } catch (DomainException $e) {
            return redirect()->route('rotas.index')->with('error', $e->getMessage());
        } catch (Throwable $e) {
            report($e);

            return redirect()->route('rotas.index')->with('error', 'Erro ao cadastrar a rota de entrega: ');
        }
    }

    public function update(UpdateRotaRequest $request, Rota $rota, RotaService $rotas)
    {
        try {
            $rotas->atualizar($rota, RotaData::fromArray($request->validated()));

            return redirect()->route('rotas.index')->with('success', 'Rota atualizada com sucesso!');
        } catch (DomainException $e) {
            return redirect()->route('rotas.index')->with('error', $e->getMessage());
        } catch (Throwable $e) {
            report($e);

            return redirect()->route('rotas.index')->with('error', 'Erro ao atualizar rota.');
        }
    }

    public function show(Rota $rotas, HistoricoRepository $historicos)
    {
        $data = $rotas->loadMissing(['pedidos.notaFiscal.remetente', 'pedidos.notaFiscal.destinatario', 'pedidos.historicos', 'motorista.usuario', 'veiculo', 'origem', 'destino', 'historicos']);

        return view('rotas.show', [
            'data' => $data,
            'historicos' => $historicos->semDuplicidades($data->historicos),
            'mapboxToken' => env('MAPBOX_ACCESS_TOKEN'),
        ]);
    }

    public function editAdmin(Rota $rota, RotaService $rotas)
    {
        return view('rotas.admin.edit', $rotas->dadosEdicaoAdmin($rota));
    }

    public function updateAdmin(AdminUpdateRotaRequest $request, Rota $rota, RotaService $rotas)
    {
        try {
            $rotas->atualizarAdmin(
                $rota,
                AdminRotaUpdateData::fromArray($request->validated()),
                $request->user()?->id_usuario,
            );

            return redirect()
                ->route('rotas.admin.edit', $rota)
                ->with('success', 'Rota atualizada com sucesso!');
        } catch (DomainException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Erro ao atualizar rota.');
        }
    }

    public function gerarRomaneio(Rota $rota, RomaneioPdfService $romaneio)
    {
        try {
            $pdf = $romaneio->gerar($rota);
            $filename = 'romaneio-rota-'.$rota->id_rotas.'.pdf';

            return response($pdf, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$filename.'"',
            ]);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        } catch (Throwable $e) {
            report($e);

            return back()->with('error', 'Erro ao gerar romaneio da rota.');
        }
    }

    public function historico(
        RotaRequest $request,
        ComprovanteEntregaService $comprovantes,
        RotaHistoricoService $historicos,
    ) {
        try {
            $foto = $comprovantes->armazenar($request->file('foto'));
            $historicos->registrarMovimentacao(
                RegistrarHistoricoRotaData::fromArray($request->validated(), $foto)
            );

            return redirect()->route('rotas.index')->with('success', 'Rota alterada com sucesso!');
        } catch (DomainException $e) {
            return redirect()->route('rotas.index')->with('error', $e->getMessage());
        } catch (Throwable $e) {
            report($e);

            return redirect()->route('rotas.index')->with('error', 'Erro ao alterar a rota: ');
        }
    }

    public function destroy(Rota $rota)
    {
        //
    }
}
