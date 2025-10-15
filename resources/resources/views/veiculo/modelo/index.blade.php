@extends('layouts.app')

@section('content')

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<div class="container mt-4">
  <div class="card shadow rounded-4 border-0">
    <!-- Header com estilo DNA -->
    <div class="card-header d-flex justify-content-between align-items-center rounded-top-4"
         style="background: linear-gradient(135deg, #264653, #2a9d8f); color: #fff;">
      <h5 class="mb-0"><i class="bi bi-truck-front me-2"></i>Cadastro de Modelo de Veículo</h5>
    </div>

    <!-- Corpo do formulário clean -->
    <div class="card-body bg-white">
      <form action="{{ route('modelo.store') }}" method="POST">
        @csrf

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="marca" class="form-label fw-semibold">Marca</label>
            <input type="text" class="form-control" id="marca" name="marca" placeholder="Ex: Scania" required>
          </div>

          <div class="col-md-6">
            <label for="modelo" class="form-label fw-semibold">Modelo</label>
            <input type="text" class="form-control" id="modelo" name="modelo" placeholder="Ex: R440" required>
          </div>
        </div>

        <div class="mb-3">
          <label for="categoria" class="form-label fw-semibold"><i class="bi bi-truck me-1"></i>Categoria do Caminhão</label>
          <select class="form-select" id="categoria" name="categoria" required>
            <option value="" disabled selected>Selecione a categoria</option>
            <option value="Cavalo Mecânico">Cavalo Mecânico</option>
            <option value="Truck">Truck</option>
            <option value="Toco">Toco</option>
            <option value="Bitrem">Bitrem</option>
            <option value="Rodotrem">Rodotrem</option>
          </select>
        </div>

        <input type="hidden" name="status" value="ativo">

        <div class="mb-3">
          <label for="descricao" class="form-label fw-semibold">Descrição</label>
          <textarea class="form-control" id="descricao" name="descricao" rows="3" placeholder="Descreva o modelo, características, ano, etc."></textarea>
        </div>

        <div class="d-flex justify-content-end">
          <button type="submit" class="btn btn-warning fw-bold">
            <i class="bi bi-check-circle me-1"></i>Salvar
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

@endsection
