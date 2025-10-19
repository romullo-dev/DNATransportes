<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>DNA Transportes</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    <style>
        html,
        body {
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
            background-color: #12181F;
            /* cor principal do sistema */
            color: #213d55;
            /* cor de texto padrão clara */
        }

        main {
            flex: 1;
        }

        /* Navbar moderna */
        .navbar {
            background: linear-gradient(90deg, #2c313a, #3e4754);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.4rem;
            color: #ffc107 !important;
            /* marca em dourado */
        }

        .nav-link {
            color: #f1f1f1 !important;
            transition: color 0.3s, transform 0.3s;
        }

        .nav-link:hover {
            color: #ffc107 !important;
            transform: scale(1.05);
        }

        .dropdown-menu-dark {
            background-color: #2c313a;
            border: 1px solid #ffc10733;
        }

        .dropdown-item:hover {
            background-color: #ffc10733;
        }

        /* Cards, containers etc. */
        .card {
            background-color: #f8f9fa;
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.4);
        }

        .btn-warning {
            background-color: #ffc107;
            color: #000;
            font-weight: bold;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .btn-warning:hover {
            transform: scale(1.05);
            box-shadow: 0 0.75rem 1.5rem rgba(255, 193, 7, 0.4);
        }

        footer {
            background-color: #2c313a;
            color: #aaa;
            padding: 1rem 0;
            border-top: 2px solid #ffc10733;
        }

        hr {
            border-top: 1px solid #ffc10733;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('dashboard') }}">DNA Transportes</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('pedidos.rastreamento') }}">
                                    <i class="bi bi-truck-front-fill me-1"></i> Rastreamento
                                </a>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-white" href="#" id="operacionalDropdown"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-gear-wide-connected me-1"></i> Operacional
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                                    <li><a class="dropdown-item" href="{{ route('rotas.create') }}">
                                            <i class="bi bi-signpost"></i> Criação de Rotas</a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('rotas.index') }}">
                                            <i class="bi bi-map-fill"></i> Rotas </a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('pedidos.index') }}"><i
                                                class="bi bi-box"></i> Pedidos</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-white" href="#" id="cadastrosDropdown"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person-plus-fill me-1"></i> Cadastros
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2"
                                            href="{{ route('centro.index') }}">
                                            <i class="bi bi-truck fs-5 text-primary"></i> Centro de Distribuição
                                        </a>
                                    </li>

                                    <li class="dropdown-divider"></li>

                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2"
                                            href="{{ route('veiculo.index') }}">
                                            <i class="bi bi-truck fs-5 text-primary"></i> Veículo
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2"
                                            href="{{ route('modelo.index') }}">
                                            <i class="bi bi-gear fs-5 text-success"></i> Modelo do Veículo
                                        </a>
                                    <li class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('motorista.index') }}"><i
                                                class="bi bi-person-badge"></i> Motorista</a></li>
                                    <li><a class="dropdown-item" href="{{ route('read-user') }}"><i
                                                class="bi bi-people"></i> Usuários</a></li>
                                </ul>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2"
                                            href="{{ route('veiculo.index') }}">
                                            <i class="bi bi-truck fs-5 text-primary"></i> Veículo
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2"
                                            href="{{ route('modelo.index') }}">
                                            <i class="bi bi-gear fs-5 text-success"></i> Modelo do Veículo
                                        </a>
                                    <li class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('motorista.index') }}"><i
                                                class="bi bi-person-badge"></i> Motorista</a></li>
                                    <li><a class="dropdown-item" href="{{ route('read-user') }}"><i
                                                class="bi bi-people"></i> Usuários</a></li>

                                </ul>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-white" href="#" id="ajusteDropdown"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-gear-fill me-1"></i> Ajuste
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark"
                                    aria-labelledby="ajusteDropdown">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('endereco.index') }}">
                                            <i class="bi bi-house-door me-2"></i> Endereço
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bi bi-person-circle me-2"></i> Perfil
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bi bi-sliders me-2"></i> Configurações
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('importacao.index') }}">
                                    <i class="bi bi-calculator me-1"></i> Importar XML
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('pedidos.painel') }}">
                                    <i class="bi bi-airplane-engines-fill me-1"></i> Painel
                                </a>
                            </li>

                            <!-- Usuário / Logout -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#"
                                    role="button" data-bs-toggle="dropdown" style="color: #fff;">

                                    <!-- Foto do usuário -->
                                    @if (Auth::user()->foto)
                                        <img src="{{ asset('usuarios/' . Auth::user()->foto) }}"
                                            alt="Foto do usuário" class="rounded-circle border border-warning"
                                            style="width: 36px; height: 36px; object-fit: cover;">
                                    @else
                                        <i class="bi bi-person-circle fs-3"></i>
                                    @endif
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('show.user', ['id' => Auth::user()->id_usuario]) }}">
                                            <i class="bi bi-box-arrow-right me-2"></i> Ver usuário
                                        </a>
                                        <a class="dropdown-item" href="#"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="bi bi-box-arrow-right me-2"></i> Sair
                                        </a>
                                    </li>
                                </ul>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>

                        @yield('menu')
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="container py-4">
        @yield('content')
    </main>

    <footer class="text-center mt-auto">
        <hr>
        <p>DNA Transportes &copy; {{ date('Y') }} - Todos os direitos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
