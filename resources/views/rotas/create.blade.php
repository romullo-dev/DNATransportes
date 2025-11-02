@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg rounded-4 border-0" style="background-color:#1b1f27; color:#e0e0e0;">
        <div class="card-header text-white d-flex justify-content-between align-items-center"
            style="background:linear-gradient(90deg, #be9312, #ffc107); border-top-left-radius:1rem; border-top-right-radius:1rem;">
            <h5 class="mb-0 fw-bold"><i class="bi bi-map-fill me-2"></i>Cadastrar Nova Rota</h5>
        </div>

        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success bg-success text-white border-0 rounded-3">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger bg-danger text-white border-0 rounded-3">
                    <i class="bi bi-exclamation-octagon-fill me-2"></i>{{ session('error') }}
                </div>
            @endif

            <!-- Seleção de Tipo -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="tipo" class="form-label text-warning fw-semibold">
                        <i class="bi bi-signpost-split-fill me-1"></i>Tipo da Rota
                    </label>
                    <select class="form-select border-0 shadow-sm" id="tipo" required
                        style="background-color:#2a2f3a; color:#f8f9fa;">
                        <option value="" disabled selected>Selecione o tipo</option>
                        <option value="coleta">Coleta</option>
                        <option value="transferencia">Transferência</option>
                        <option value="entrega">Entrega</option>
                    </select>
                </div>
            </div>

            <!-- ==================== FORMULÁRIO COLETA ==================== -->
            <form id="form-coleta" action="{{ route('rotas.entrega.store') }}" method="POST" class="d-none">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="id_origem" class="form-label text-warning fw-semibold">
                            <i class="bi bi-geo-alt-fill me-1"></i>Origem
                        </label>
                        <select class="form-select border-0 shadow-sm" id="id_origem" name="id_origem" required
                            style="background-color:#2a2f3a; color:#fff;">
                            <option value="" disabled selected>Selecione um CD</option>
                            @foreach ($centros as $cd)
                                <option value="{{ $cd->id_centro_distribuicao }}">{{ $cd->nome }} - {{ $cd->uf }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Distância, Previsão, Data -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="distancia" class="form-label text-warning fw-semibold">
                            <i class="bi bi-rulers me-1"></i>Distância (km)
                        </label>
                        <input type="number" class="form-control border-0 shadow-sm" id="distancia" name="distancia" required
                            style="background-color:#2a2f3a; color:#fff;">
                    </div>
                    <div class="col-md-4">
                        <label for="previsao" class="form-label text-warning fw-semibold">
                            <i class="bi bi-clock-history me-1"></i>Previsão de Chegada no Cliente
                        </label>
                        <input type="date" class="form-control border-0 shadow-sm" id="previsao" name="previsao" required
                            style="background-color:#2a2f3a; color:#fff;">
                    </div>
                    <div class="col-md-4">
                        <label for="data_inicio" class="form-label text-warning fw-semibold">
                            <i class="bi bi-calendar-event me-1"></i>Data de Início
                        </label>
                        <input type="datetime-local" class="form-control border-0 shadow-sm" id="data_inicio"
                            name="data_inicio" required style="background-color:#2a2f3a; color:#fff;">
                    </div>
                </div>

                <!-- Motorista e Veículo -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="id_motorista" class="form-label text-warning fw-semibold">
                            <i class="bi bi-person-badge-fill me-1"></i>Motorista
                        </label>
                        <select class="form-select border-0 shadow-sm" id="id_motorista" name="id_motorista" required
                            style="background-color:#2a2f3a; color:#fff;">
                            <option value="" disabled selected>Selecione um motorista</option>
                            @foreach ($motoristas as $item)
                                <option value="{{ $item->id_motorista }}">{{ $item->usuario->nome }} - {{ $item->usuario->cpf }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="id_veiculo" class="form-label text-warning fw-semibold">
                            <i class="bi bi-truck-front-fill me-1"></i>Veículo
                        </label>
                        <select class="form-select border-0 shadow-sm" id="id_veiculo" name="id_veiculo" required
                            style="background-color:#2a2f3a; color:#fff;">
                            <option value="" disabled selected>Selecione um veículo</option>
                            @foreach ($veiculos as $item)
                                <option value="{{ $item->id_Veiculo }}">{{ $item->placa }} - KG {{ $item->capacidade_kg }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Observações -->
                <div class="mb-3">
                    <label for="observacoes" class="form-label text-warning fw-semibold">
                        <i class="bi bi-chat-left-text-fill me-1"></i>Observações
                    </label>
                    <textarea class="form-control border-0 shadow-sm" id="observacoes" name="observacoes" rows="3"
                        placeholder="Informações adicionais..." style="background-color:#2a2f3a; color:#fff;"></textarea>
                </div>

                <!-- Chaves -->
                <div class="mb-3">
                    <label for="chave_nota" class="form-label text-warning fw-semibold">
                        <i class="bi bi-file-earmark-text me-1"></i>Chave(s) da Nota Fiscal
                    </label>
                    <input type="text" class="form-control border-0 shadow-sm @error('chave_nota') is-invalid @enderror"
                        id="chave_nota" name="chave_nota" placeholder="Digite as chaves, separadas por vírgula" required
                        style="background-color:#2a2f3a; color:#fff;">
                    @error('chave_nota')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <input type="hidden" name="tipo" value="coleta">

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-success px-4 py-2"
                        style="background-color:#ffc107; border:none; font-weight:600;">
                        <i class="bi bi-check2-circle me-1"></i>Salvar Coleta
                    </button>
                </div>
            </form>

            <!-- ==================== FORMULÁRIO TRANSFERÊNCIA ==================== -->
            <form id="form-transferencia" action="{{ route('rotas.store') }}" method="POST" class="d-none">
                @csrf
                @foreach ($centros as $cd)
                    @endforeach {{-- apenas pra evitar warning se não houver registros --}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label text-warning fw-semibold"><i class="bi bi-geo-alt-fill me-1"></i>Origem</label>
                        <select class="form-select border-0 shadow-sm" name="id_origem" required
                            style="background-color:#2a2f3a; color:#fff;">
                            <option value="" disabled selected>Selecione um CD</option>
                            @foreach ($centros as $cd)
                                <option value="{{ $cd->id_centro_distribuicao }}">{{ $cd->nome }} - {{ $cd->uf }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-warning fw-semibold"><i class="bi bi-geo-fill me-1"></i>Destino</label>
                        <select class="form-select border-0 shadow-sm" name="id_destino" required
                            style="background-color:#2a2f3a; color:#fff;">
                            <option value="" disabled selected>Selecione um CD</option>
                            @foreach ($centros as $cd)
                                <option value="{{ $cd->id_centro_distribuicao }}">{{ $cd->nome }} - {{ $cd->uf }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label text-warning fw-semibold"><i class="bi bi-rulers me-1"></i>Distância (km)</label>
                        <input type="number" class="form-control border-0 shadow-sm" name="distancia" required
                            style="background-color:#2a2f3a; color:#fff;">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-warning fw-semibold"><i class="bi bi-clock-history me-1"></i>Previsão de Chegada</label>
                        <input type="date" class="form-control border-0 shadow-sm" name="previsao" required
                            style="background-color:#2a2f3a; color:#fff;">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-warning fw-semibold"><i class="bi bi-calendar-event me-1"></i>Data de Início</label>
                        <input type="datetime-local" class="form-control border-0 shadow-sm" name="data_inicio" required
                            style="background-color:#2a2f3a; color:#fff;">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-warning fw-semibold"><i class="bi bi-person-badge-fill me-1"></i>Motorista</label>
                        <select class="form-select border-0 shadow-sm" name="id_motorista" required
                            style="background-color:#2a2f3a; color:#fff;">
                            <option value="" disabled selected>Selecione um motorista</option>
                            @foreach ($motoristas as $item)
                                <option value="{{ $item->id_motorista }}">{{ $item->usuario->nome }} - {{ $item->usuario->cpf }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-warning fw-semibold"><i class="bi bi-truck-front-fill me-1"></i>Veículo</label>
                        <select class="form-select border-0 shadow-sm" name="id_veiculo" required
                            style="background-color:#2a2f3a; color:#fff;">
                            <option value="" disabled selected>Selecione um veículo</option>
                            @foreach ($veiculos as $item)
                                <option value="{{ $item->id_Veiculo }}">{{ $item->placa }} - KG {{ $item->capacidade_kg }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-warning fw-semibold"><i class="bi bi-chat-left-text-fill me-1"></i>Observações</label>
                    <textarea class="form-control border-0 shadow-sm" name="observacoes" rows="3" placeholder="Informações adicionais..."
                        style="background-color:#2a2f3a; color:#fff;"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label text-warning fw-semibold"><i class="bi bi-file-earmark-text me-1"></i>Chave(s) da Nota Fiscal</label>
                    <input type="text" class="form-control border-0 shadow-sm" name="chave_nota" required
                        placeholder="Digite as chaves, separadas por vírgula" style="background-color:#2a2f3a; color:#fff;">
                </div>

                <input type="hidden" name="tipo" value="transferencia">

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-success px-4 py-2"
                        style="background-color:#ffc107; border:none; font-weight:600;">
                        <i class="bi bi-check2-circle me-1"></i>Salvar Transferência
                    </button>
                </div>
            </form>

            <!-- ==================== FORMULÁRIO ENTREGA ==================== -->
            <form id="form-entrega" action="{{ route('rotas.entrega.store') }}" method="POST" class="d-none">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label text-warning fw-semibold"><i class="bi bi-geo-alt-fill me-1"></i>Origem</label>
                        <select class="form-select border-0 shadow-sm" name="id_origem" required
                            style="background-color:#2a2f3a; color:#fff;">
                            <option value="" disabled selected>Selecione um CD</option>
                            @foreach ($centros as $cd)
                                <option value="{{ $cd->id_centro_distribuicao }}">{{ $cd->nome }} - {{ $cd->uf }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label text-warning fw-semibold"><i class="bi bi-rulers me-1"></i>Distância (km)</label>
                        <input type="number" class="form-control border-0 shadow-sm" name="distancia" required
                            style="background-color:#2a2f3a; color:#fff;">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-warning fw-semibold"><i class="bi bi-clock-history me-1"></i>Previsão de Chegada</label>
                        <input type="date" class="form-control border-0 shadow-sm" name="previsao" required
                            style="background-color:#2a2f3a; color:#fff;">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-warning fw-semibold"><i class="bi bi-calendar-event me-1"></i>Data de Início</label>
                        <input type="datetime-local" class="form-control border-0 shadow-sm" name="data_inicio" required
                            style="background-color:#2a2f3a; color:#fff;">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-warning fw-semibold"><i class="bi bi-person-badge-fill me-1"></i>Motorista</label>
                        <select class="form-select border-0 shadow-sm" name="id_motorista" required
                            style="background-color:#2a2f3a; color:#fff;">
                            <option value="" disabled selected>Selecione um motorista</option>
                            @foreach ($motoristas as $item)
                                <option value="{{ $item->id_motorista }}">{{ $item->usuario->nome }} - {{ $item->usuario->cpf }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-warning fw-semibold"><i class="bi bi-truck-front-fill me-1"></i>Veículo</label>
                        <select class="form-select border-0 shadow-sm" name="id_veiculo" required
                            style="background-color:#2a2f3a; color:#fff;">
                            <option value="" disabled selected>Selecione um veículo</option>
                            @foreach ($veiculos as $item)
                                <option value="{{ $item->id_Veiculo }}">{{ $item->placa }} - KG {{ $item->capacidade_kg }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-warning fw-semibold"><i class="bi bi-chat-left-text-fill me-1"></i>Observações</label>
                    <textarea class="form-control border-0 shadow-sm" name="observacoes" rows="3" placeholder="Informações adicionais..."
                        style="background-color:#2a2f3a; color:#fff;"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label text-warning fw-semibold"><i class="bi bi-file-earmark-text me-1"></i>Chave(s) da Nota Fiscal</label>
                    <input type="text" class="form-control border-0 shadow-sm" name="chave_nota" required
                        placeholder="Digite as chaves, separadas por vírgula" style="background-color:#2a2f3a; color:#fff;">
                </div>

                <input type="hidden" name="tipo" value="entrega">

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-success px-4 py-2"
                        style="background-color:#ffc107; border:none; font-weight:600;">
                        <i class="bi bi-check2-circle me-1"></i>Salvar Entrega
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tipoSelect = document.getElementById('tipo');
        const forms = {
            coleta: document.getElementById('form-coleta'),
            transferencia: document.getElementById('form-transferencia'),
            entrega: document.getElementById('form-entrega')
        };

        const esconderTodos = () => Object.values(forms).forEach(f => f.classList.add('d-none'));

        tipoSelect.addEventListener('change', function() {
            esconderTodos();
            const tipo = this.value;
            if (forms[tipo]) forms[tipo].classList.remove('d-none');
        });
    });
</script>

