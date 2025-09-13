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
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="busca" class="form-control" placeholder="Buscar por nome ou CPF" value="#">
            </div>
            <div class="col-md-3">
                <select name="tipo" class="form-select">
                    <option value="">Tipo de Usuário</option>
                    {{-- <option value="admin" {{ request('tipo') == 'admin' ? 'selected' : '' }}>Admin</option> --}}

                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Status</option>
                    {{-- <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>Ativo</option> --}}
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="bi bi-search"></i> Filtrar
                </button>
            </div>
        </form>

        {{-- Tabela de motoristas --}}
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-hover align-middle table-bordered bg-white">
                <thead class="table-light">
                    <tr>
                        <th>Data de Emissão</th>
                        <th>Cliente</th>
                        <th>Destinatário</th>
                        <th>CNPJ do cliente</th>
                        <th>UF</th>
                        <th>Valor do Frete</th>
                        <th></th>


                    </tr>
                </thead>
                <tbody>

                    @foreach ($result as $pedidos)
                        <td>{{ \Carbon\Carbon::parse($pedidos->created_at)->format('d/m/Y') }}</td>
                        <td>{{ $pedidos->notaFiscal->remetente->nome }}</td>
                        <td>{{ $pedidos->notaFiscal->destinatario->nome }}</td>
                        <td>{{ $pedidos->notaFiscal->destinatario->documento }}</td>
                        <td>{{ $pedidos->notaFiscal->enderecoRemetente->uf }}</td>
                        <td>R$ {{ $pedidos->frete->valor_frete }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal"
                                data-bs-target="#modalShow{{ $pedidos->id_pedido }}">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                        </td>
                    @endforeach

                </tbody>
            </table>
        </div>
        <br>

         <div class="d-flex justify-content-center">
            {{ $result->links('pagination::bootstrap-5') }}
        </div> 

                @include('pedido.modais.show')

    </div>
@endsection
