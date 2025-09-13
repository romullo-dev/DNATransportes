@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh; background: linear-gradient(135deg, #002b5c, #005f8f); font-family: 'Segoe UI', sans-serif;">
        <div class="upload-card bg-white rounded-4 shadow p-4" style="max-width: 500px; width: 100%;">
            <h1 class="text-center mb-4" style="color: #002b5c;">Enviar arquivo XML</h1>

            @if(session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('importacao.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <input type="file" class="form-control" name="xml" accept=".xml" required>
                </div>
                <button type="submit" class="btn btn-primary w-100" style="background-color: #005f8f; border: none;">Enviar</button>
            </form>

            @if ($errors->any())
                <div class="alert alert-danger mt-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
@endsection
