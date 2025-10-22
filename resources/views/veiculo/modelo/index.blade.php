@extends('layouts.app')

@section('content')

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-pill text-center shadow-sm fw-semibold" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show rounded-pill text-center shadow-sm fw-semibold" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<div class="container-fluid py-5" style="background-color: #12181F; min-height: 100vh; color: #f1f1f1;">
  <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
    <!-- Cabeçalho -->
    <div class="card-header d-flex justify-content-between align-items-center p-3"
         style="background: linear-gradient(90deg, #017aaa, #2a9d8f); color: #fff;">
      <h5 class="mb-0 fw-bold">
        <i class="bi bi-truck-front-fill me-2"></i> Cadastro de Modelo de Veículo
      </h5>
    </div>

    <!-- Corpo -->
    <div class="card-body bg-dark text-light p-4">
      <form action="{{ route('modelo.store') }}" method="POST">
        @csrf

        <div class="row mb-4">
          <div class="col-md-6">
            <label for="marca" class="form-label text-warning fw-semibold">
              <i class="bi bi-building me-1"></i> Marca
            </label>
            <input type="text" class="form-control bg-dark text-light border-secondary rounded-pill px-3"
                   id="marca" name="marca" placeholder="Ex: Scania" required>
          </div>

          <div class="col-md-6">
            <label for="modelo" class="form-label text-warning fw-semibold">
              <i class="bi bi-card-text me-1"></i> Modelo
            </label>
            <input type="text" class="form-control bg-dark text-light border-secondary rounded-pill px-3"
                   id="modelo" name="modelo" placeholder="Ex: R440" required>
          </div>
        </div>

        <div class="mb-4">
          <label for="categoria" class="form-label text-warning fw-semibold">
            <i class="bi bi-truck me-1"></i> Categoria do Caminhão
          </label>
          <select class="form-select bg-dark text-light border-secondary rounded-pill px-3"
                  id="categoria" name="categoria" required>
            <option value="" disabled selected>Selecione a categoria</option>
            <option value="Cavalo Mecânico">Cavalo Mecânico</option>
            <option value="Truck">Truck</option>
            <option value="Toco">Toco</option>
            <option value="Bitrem">Bitrem</option>
            <option value="Rodotrem">Rodotrem</option>
          </select>
        </div>

        <input type="hidden" name="status" value="ativo">

        <div class="mb-4">
          <label for="descricao" class="form-label text-warning fw-semibold">
            <i class="bi bi-card-text me-1"></i> Descrição
          </label>
          <textarea class="form-control bg-dark text-light border-secondary rounded-4"
                    id="descricao" name="descricao" rows="3"
                    placeholder="Descreva o modelo, características, ano, etc."></textarea>
        </div>

        <div class="d-flex justify-content-end">
          <button type="submit" class="btn btn-gradient fw-bold px-4 py-2 rounded-pill">
            <i class="bi bi-check-circle-fill me-2"></i>Salvar Modelo
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

{{-- Estilo DNA Transportes --}}
<style>
  .btn-gradient {
    background: linear-gradient(90deg, #017aaa, #2a9d8f);
    color: #fff;
    border: none;
    transition: 0.3s;
  }

  .btn-gradient:hover {
    background: linear-gradient(90deg, #2a9d8f, #017aaa);
    box-shadow: 0 3px 10px rgba(42, 157, 143, 0.4);
    transform: translateY(-1px);
  }

  .form-control:focus, .form-select:focus {
    border-color: #2a9d8f;
    box-shadow: 0 0 0 0.25rem rgba(42, 157, 143, 0.25);
  }

  ::placeholder {
    color: #aaa !important;
  }
</style>

@endsection
