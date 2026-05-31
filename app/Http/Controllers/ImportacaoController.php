<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportarNfeRequest;
use App\Services\ImportacaoNfeService;
use DomainException;
use Throwable;

class ImportacaoController extends Controller
{
    public function index()
    {
        return view('import.index');
    }

    public function store(ImportarNfeRequest $request, ImportacaoNfeService $importacaoNfe)
    {
        try {
            $pedido = $importacaoNfe->importar($request->file('xml'));

            return redirect()->back()->with('success', 'Arquivo XML importado com sucesso! Pedido: '.$pedido->codigo_rastreamento);
        } catch (DomainException $e) {
            return redirect()->route('importacao.index')->with('error', $e->getMessage());
        } catch (Throwable $e) {
            report($e);

            return redirect()->route('importacao.index')->with('error', 'Erro ao importar: ');
        }
    }
}
