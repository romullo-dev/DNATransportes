@extends('layouts.app')

@section('content')
    {{-- Mensagens de sucesso/erro --}}
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="container-fluid py-4">

        {{-- Filtros --}}
        <form method="GET" class="row g-2 mb-4">
            <div class="col-md-4 col-12">
                <input type="text" name="busca" class="form-control" placeholder="Buscar por nome ou CPF" value="{{ request('busca') }}">
            </div>
            <div class="col-md-3 col-12">
                <select name="tipo" class="form-select">
                    <option value="">Tipo de Usuário</option>
                    {{-- <option value="admin" {{ request('tipo') == 'admin' ? 'selected' : '' }}>Admin</option> --}}
                </select>
            </div>
            <div class="col-md-3 col-12">
                <select name="status" class="form-select">
                    <option value="">Status</option>
                    {{-- <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>Ativo</option> --}}
                </select>
            </div>
            <div class="col-md-2 col-12">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="bi bi-search"></i> Filtrar
                </button>
            </div>
        </form>

        {{-- Tabela de Pedidos --}}
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-hover align-middle table-bordered bg-white" style="font-size: 0.9rem;">
                <thead class="table-light">
                    <tr>
                        <th>Data de Emissão</th>
                        <th>Cliente</th>
                        <th>Destinatário</th>
                        <th>CNPJ do Cliente</th>
                        <th>UF</th>
                        <th>Valor do Frete</th>
                        <th>Número da Nota</th>
                        <th>Código de Rastreamento</th>
                        <th>Status da Nota</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($result as $pedido)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($pedido->created_at)->format('d/m/Y') }}</td>
                            <td>{{ $pedido->notaFiscal->remetente->nome }}</td>
                            <td>{{ $pedido->notaFiscal->destinatario->nome }}</td>
                            <td>{{ $pedido->notaFiscal->destinatario->documento }}</td>
                            <td>{{ $pedido->notaFiscal->enderecoRemetente->uf }}</td>
                            <td>R$ {{ number_format($pedido->frete->valor_frete, 2, ',', '.') }}</td>
                            <td>{{ $pedido->notaFiscal->numero_nfe }}</td>
                            <td>{{ $pedido->codigo_rastreamento }}</td>
                            <td>{{ ucfirst($pedido->status) }}</td>

                            <td class="text-center">
    <div class="d-flex justify-content-center align-items-center gap-2">
        <!-- Visualizar Pedido -->
        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalShow{{ $pedido->id_pedido }}">
            <i class="bi bi-eye-fill"></i> 
        </button>

        <!-- Histórico de Pedido -->
        <a href="{{ route('pedidos.edit', $pedido->id_pedido) }}" class="btn btn-sm btn-info text-white">
            <i class="bi bi-clock-history"></i> 
        </a>
    </div>
</td>




                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <br>

        {{-- Paginação --}}
        <div class="d-flex justify-content-center">
            {{ $result->links('pagination::bootstrap-5') }}
        </div>

        <!-- Modal: Visualizar Pedido -->
        @include('pedido.modais.show')
    </div>
@endsection
