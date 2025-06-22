<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background-color: #3e84b0;">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold text-white" href="index.php?home">DNA Transportes</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

        <li class="nav-item">
          <a class="nav-link text-white" href="index.php?rastreio"><i class="bi bi-truck"></i> Rastreio</a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" id="operacionalDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-gear"></i> Operacional
          </a>
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
            <li><a class="dropdown-item" href="index.php?rotas">Rotas</a></li>
            <li><a class="dropdown-item" href="index.php?rotasAjuste">Ajuste de Rotas</a></li>
            <li><a class="dropdown-item" href="index.php?pedidos">Pedidos</a></li>
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" id="cadastrosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-plus"></i> Cadastros
          </a>
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
            <li><a class="dropdown-item" href="index.php?veiculo">Veículo</a></li>
            <li><a class="dropdown-item" href="index.php?Motorista">Motorista</a></li>
            <li><a class="dropdown-item" href="index.php?Usuarios">Usuários</a></li>
          </ul>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white" href="index.php?cotacao"><i class="bi bi-box-seam"></i> Cotação</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white" href="index.php?AWB"><i class="bi bi-airplane"></i> Tracking Aéreo</a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-light ms-2" href="#" role="button" data-bs-toggle="dropdown">
            <i class="bi bi-person-circle"></i> <?= $_SESSION['user'] ?? 'Visitante' ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
            <li><a class="dropdown-item" href="logout.php">Sair</a></li>
          </ul>
        </li>

      </ul>
    </div>
  </div>
</nav>
