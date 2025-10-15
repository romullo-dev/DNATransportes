@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card shadow rounded-4">
            <div class="card-header text-white d-flex justify-content-between align-items-center"
                style="background-color: #01101a;">
                <h5 class="mb-0"><i class="bi bi-map-fill me-2"></i>Cadastrar Nova Rota</h5>
            </div>
            <div class="card-body">

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <!-- Seleção de Tipo -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="tipo" class="form-label"><i class="bi bi-signpost-split-fill me-1"></i>Tipo da
                            Rota</label>
                        <select class="form-select" id="tipo" required>
                            <option value="" disabled selected>Selecione o tipo</option>
                            <option value="coleta">Coleta</option>
                            <option value="transferencia">Transferência</option>
                            <option value="entrega">Entrega</option>
                        </select>
                    </div>
                </div>

                <!-- Formulário Coleta -->
                <form id="form-coleta" action="{{ route('rotas.entrega.store') }}" method="POST" class="d-none">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="id_origem" class="form-label"><i class="bi bi-geo-alt-fill me-1"></i>Origem</label>
                            <select class="form-select" id="id_origem" name="id_origem" required>
                                <option value="" disabled selected>Selecione um CD</option>
                                @foreach ($centros as $cd)
                                    <option value="{{ $cd->id_centro_distribuicao }}">{{ $cd->nome }} -
                                        {{ $cd->uf }} </option>
                                @endforeach
                            </select>
                        </div>


                    </div>

                    <!-- Distância, Previsão, Data de Saída -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="distancia" class="form-label"><i class="bi bi-rulers me-1"></i>Distância
                                (km)</label>
                            <input type="number" class="form-control" id="distancia" name="distancia" required>
                        </div>
                        <div class="col-md-4">
                            <label for="previsao" class="form-label"><i class="bi bi-clock-history me-1"></i>Previsão de
                                Chegada no Cliente</label>
                            <input type="date" class="form-control" id="previsao" name="previsao" required>
                        </div>
                        <div class="col-md-4">
                            <label for="data_inicio" class="form-label"><i class="bi bi-calendar-event me-1"></i>Data de
                                Início</label>
                            <input type="datetime-local" class="form-control" id="data_inicio" name="data_inicio" required>
                        </div>
                    </div>

                    <!-- Motorista & Veículo -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="id_motorista" class="form-label"><i
                                    class="bi bi-person-badge-fill me-1"></i>Motorista</label>
                            <select class="form-select" id="id_motorista" name="id_motorista" required>
                                <option value="" disabled selected>Selecione um motorista</option>
                                @foreach ($motoristas as $item)
                                    <option value="{{ $item->id_motorista }}">{{ $item->usuario->nome }} -
                                        {{ $item->usuario->cpf }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="id_veiculo" class="form-label"><i
                                    class="bi bi-truck-front-fill me-1"></i>Veículo</label>
                            <select class="form-select" id="id_veiculo" name="id_veiculo" required>
                                <option value="" disabled selected>Selecione um veículo</option>

                                @foreach ($veiculos as $item)
                                    <option value="{{ $item->id_Veiculo }}">{{ $item->placa }} - KG
                                        {{ $item->capacidade_kg }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Observações -->
                    <div class="mb-3">
                        <label for="observacoes" class="form-label"><i
                                class="bi bi-chat-left-text-fill me-1"></i>Observações</label>
                        <textarea class="form-control" id="observacoes" name="observacoes" rows="3"
                            placeholder="Informações adicionais..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="chave_nota" class="form-label"><i class="bi bi-file-earmark-text me-1"></i>Chave(s)
                            da Nota Fiscal</label>
                        <input type="text" class="form-control @error('chave_nota') is-invalid @enderror"
                            id="chave_nota" name="chave_nota" placeholder="Digite as chaves, separadas por vírgula"
                            required>
                        @error('chave_nota')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <input type="hidden" name="tipo" value="coleta">

                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-success">Salvar Entrega</button>
                    </div>
                </form>

                <!-- Formulário Transferência -->
                <form id="form-transferencia" action="{{ route('rotas.store') }}" method="POST" class="d-none">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="id_origem" class="form-label"><i
                                    class="bi bi-geo-alt-fill me-1"></i>Origem</label>
                            <select class="form-select" id="id_origem" name="id_origem" required>
                                <option value="" disabled selected>Selecione um CD</option>
                                @foreach ($centros as $cd)
                                    <option value="{{ $cd->id_centro_distribuicao }}">{{ $cd->nome }} -
                                        {{ $cd->uf }} </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="id_destino" class="form-label"><i class="bi bi-geo-fill me-1"></i>Destino</label>
                            <select class="form-select" id="id_destino" name="id_destino" required>
                                <option value="" disabled selected>Selecione um CD</option>
                                @foreach ($centros as $cd)
                                    <option value="{{ $cd->id_centro_distribuicao }}">{{ $cd->nome }} -
                                        {{ $cd->uf }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Distância, Previsão, Data de Saída -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="distancia" class="form-label"><i class="bi bi-rulers me-1"></i>Distância
                                (km)</label>
                            <input type="number" class="form-control" id="distancia" name="distancia" required>
                        </div>
                        <div class="col-md-4">
                            <label for="previsao" class="form-label"><i class="bi bi-clock-history me-1"></i>Previsão de
                                Chegada</label>
                            <input type="date" class="form-control" id="previsao" name="previsao" required>
                        </div>
                        <div class="col-md-4">
                            <label for="data_inicio" class="form-label"><i class="bi bi-calendar-event me-1"></i>Data de
                                Início</label>
                            <input type="datetime-local" class="form-control" id="data_inicio" name="data_inicio"
                                required>
                        </div>
                    </div>

                    <!-- Motorista & Veículo -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="id_motorista" class="form-label"><i
                                    class="bi bi-person-badge-fill me-1"></i>Motorista</label>
                            <select class="form-select" id="id_motorista" name="id_motorista" required>
                                <option value="" disabled selected>Selecione um motorista</option>
                                @foreach ($motoristas as $item)
                                    <option value="{{ $item->id_motorista }}">{{ $item->usuario->nome }} -
                                        {{ $item->usuario->cpf }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="id_veiculo" class="form-label"><i
                                    class="bi bi-truck-front-fill me-1"></i>Veículo</label>
                            <select class="form-select" id="id_veiculo" name="id_veiculo" required>
                                <option value="" disabled selected>Selecione um veículo</option>

                                @foreach ($veiculos as $item)
                                    <option value="{{ $item->id_Veiculo }}">{{ $item->placa }} - KG
                                        {{ $item->capacidade_kg }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Observações -->
                    <div class="mb-3">
                        <label for="observacoes" class="form-label"><i
                                class="bi bi-chat-left-text-fill me-1"></i>Observações</label>
                        <textarea class="form-control" id="observacoes" name="observacoes" rows="3"
                            placeholder="Informações adicionais..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="chave_nota" class="form-label"><i class="bi bi-file-earmark-text me-1"></i>Chave(s)
                            da Nota Fiscal</label>
                        <input type="text" class="form-control @error('chave_nota') is-invalid @enderror"
                            id="chave_nota" name="chave_nota" placeholder="Digite as chaves, separadas por vírgula"
                            required>
                        @error('chave_nota')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    <input type="hidden" name="tipo" value="transferencia">

                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-success"><i class="bi bi-check2-circle me-1"></i>Salvar
                            Transferência</button>
                    </div>
                </form>

                <!-- Formulário Entrega -->
                <form id="form-entrega" action="{{ route('rotas.entrega.store') }}" method="POST" class="d-none">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="id_origem" class="form-label"><i
                                    class="bi bi-geo-alt-fill me-1"></i>Origem</label>
                            <select class="form-select" id="id_origem" name="id_origem" required>
                                <option value="" disabled selected>Selecione um CD</option>
                                @foreach ($centros as $cd)
                                    <option value="{{ $cd->id_centro_distribuicao }}">{{ $cd->nome }} -
                                        {{ $cd->uf }} </option>
                                @endforeach
                            </select>
                        </div>


                    </div>

                    <!-- Distância, Previsão, Data de Saída -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="distancia" class="form-label"><i class="bi bi-rulers me-1"></i>Distância
                                (km)</label>
                            <input type="number" class="form-control" id="distancia" name="distancia" required>
                        </div>
                        <div class="col-md-4">
                            <label for="previsao" class="form-label"><i class="bi bi-clock-history me-1"></i>Previsão de
                                Chegada</label>
                            <input type="date" class="form-control" id="previsao" name="previsao" required>
                        </div>
                        <div class="col-md-4">
                            <label for="data_inicio" class="form-label"><i class="bi bi-calendar-event me-1"></i>Data de
                                Início</label>
                            <input type="datetime-local" class="form-control" id="data_inicio" name="data_inicio"
                                required>
                        </div>
                    </div>

                    <!-- Motorista & Veículo -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="id_motorista" class="form-label"><i
                                    class="bi bi-person-badge-fill me-1"></i>Motorista</label>
                            <select class="form-select" id="id_motorista" name="id_motorista" required>
                                <option value="" disabled selected>Selecione um motorista</option>
                                @foreach ($motoristas as $item)
                                    <option value="{{ $item->id_motorista }}">{{ $item->usuario->nome }} -
                                        {{ $item->usuario->cpf }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="id_veiculo" class="form-label"><i
                                    class="bi bi-truck-front-fill me-1"></i>Veículo</label>
                            <select class="form-select" id="id_veiculo" name="id_veiculo" required>
                                <option value="" disabled selected>Selecione um veículo</option>

                                @foreach ($veiculos as $item)
                                    <option value="{{ $item->id_Veiculo }}">{{ $item->placa }} - KG
                                        {{ $item->capacidade_kg }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="observacoes" class="form-label"><i
                                class="bi bi-chat-left-text-fill me-1"></i>Observações</label>
                        <textarea class="form-control" id="observacoes" name="observacoes" rows="3"
                            placeholder="Informações adicionais..."></textarea>
                    </div>

                   <div class="mb-3">
                        <label for="chave_nota" class="form-label"><i class="bi bi-file-earmark-text me-1"></i>Chave(s)
                            da Nota Fiscal</label>
                        <input type="text" class="form-control @error('chave_nota') is-invalid @enderror"
                            id="chave_nota" name="chave_nota" placeholder="Digite as chaves, separadas por vírgula"
                            required>
                        @error('chave_nota')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <input type="hidden" name="tipo" value="entrega">

                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-success">Salvar Entrega</button>
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

        function esconderTodos() {
            Object.values(forms).forEach(form => form.classList.add('d-none'));
        }

        tipoSelect.addEventListener('change', function() {
            const tipo = this.value; 
            esconderTodos(); 
            if (forms[tipo]) {
                forms[tipo].classList.remove('d-none'); 
            }
        });
    });
</script>