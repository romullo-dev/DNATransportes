<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/CSS/usuarios.css">

    <title>Consulta de Usuários</title>
</head>

<body>

    <?= $menu ?>

    <br><br>
    <br><br>
    <br>
    <div class="container-fluid py-5 px-4">

    


        <!-- Botão para adicionar novo motorista -->
        <div class="d-flex justify-content-end mb-3">
            <button class="btn text-white" style="background-color: #3e84b0;" data-bs-toggle="modal" data-bs-target="#modalInserirMotorista">
                <i class="bi bi-truck"></i> Adicionar Motorista
            </button>
        </div>

        <br>




        <!-- Card de consulta -->
        <div class="card shadow mb-5">
            <div class="card-body">
                <form method="post" action="index.php" class="row g-4 align-items-end">
                    <div class="col-lg-9 col-md-8">
                        <label for="nomeMotorista" class="form-label">Nome do Motorista</label>
                        <input type="text" name="nomeMotorista" class="form-control" placeholder="Digite o nome...">
                    </div>
                    <div class="col-lg-3 col-md-4 d-grid">
                        <button type="submit" name="consultar_motorista" class="btn btn-primary">
                            <i class="bi bi-search"></i> Consultar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Listagem de motoristas -->
        <div class="card shadow">
            <div class="card-header text-white text-center" style="background-color: #3e84b0;">
                Lista de Motoristas
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach ($resultado as $valor): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm border-0 position-relative">

                                <!-- Dropdown de ações -->
                                <div class="dropdown position-absolute top-0 end-0 mt-2 me-2">
                                    <button class="btn btn-light btn-sm" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow">
                                        <li>
                                            <button class="dropdown-item text-warning" data-bs-toggle="modal" data-bs-target="#modal_alterar_motorista<?= $valor->id_Motorista ?>">
                                                <i class="bi bi-pencil-square"></i> Editar
                                            </button>

                                        </li>
                                        <li>
                                            <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#modal_excluir_Motorista<?= $valor->id_Motorista ?>">
                                                <i class="bi bi-trash"></i> Excluir
                                            </button>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Card com info do motorista -->
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                                            <i class="bi bi-person-circle fs-2"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h5 class="mb-0"><?= $valor->nomeCompleto ?></h5>
                                            <small class="text-muted">ID Usuário: <?= $valor->id_usuario ?></small>
                                        </div>
                                    </div>

                                    <p class="mb-1"><i class="bi bi-card-heading text-primary"></i> CNH: <?= $valor->cnh ?></p>
                                    <p class="mb-1"><i class="bi bi-tags text-primary"></i> Categoria: <?= $valor->categoria ?></p>
                                    <p class="mb-1"><i class="bi bi-calendar-event text-primary"></i> Validade: <?= date('d/m/Y', strtotime($valor->validade_cnh)) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>




        <!-- Modal Inserir Motorista -->
        <div class="modal fade" id="modalInserirMotorista" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header text-white" style="background-color: #3e84b0;">
                        <h5 class="modal-title"><i class="bi bi-truck-front"></i> Novo Motorista</h5>
                        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="index.php" method="post" class="row g-3">

                            <!-- Código do usuário -->
                            <div class="col-md-6">
                                <label for="id_usuario" class="form-label">
                                    <i class="bi bi-person-badge"></i> Código do Usuário
                                </label>
                                <input type="number" name="id_usuario" id="id_usuario" class="form-control" placeholder="Digite o código do usuário..." required>
                            </div>

                            <!-- CNH -->
                            <div class="col-md-6">
                                <label for="cnh" class="form-label">
                                    <i class="bi bi-card-heading"></i> CNH
                                </label>
                                <input type="text" name="cnh" id="cnh" class="form-control" placeholder="Digite o número da CNH" required>
                            </div>

                            <!-- Categoria -->
                            <div class="col-md-6">
                                <label for="categoria" class="form-label">
                                    <i class="bi bi-tags"></i> Categoria
                                </label>
                                <select name="categoria" id="categoria" class="form-select" required>
                                    <option value="">Selecione...</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                    <option value="E">E</option>
                                </select>
                            </div>

                            <!-- Validade da CNH -->
                            <div class="col-md-6">
                                <label for="validade_cnh" class="form-label">
                                    <i class="bi bi-calendar-event"></i> Validade CNH
                                </label>
                                <input type="date" name="validade_cnh" id="validade_cnh" class="form-control" required>
                            </div>

                            <!-- Botões -->
                            <div class="col-12 d-flex justify-content-end mt-3">
                                <button type="submit" name="cadastrar_motorista" class="btn text-white me-2" style="background-color: #3e84b0;">
                                    <i class="bi bi-check-circle"></i> Cadastrar
                                </button>
                                <button type="reset" class="btn btn-outline-danger">
                                    <i class="bi bi-x-circle"></i> Limpar
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>



    </div>


    <?php
    foreach ($resultado as $valor) {
        // Modal de exclusão
        $this->modal_excluir_Motorista($valor->id_Motorista, $valor->nomeCompleto);

        // Modal de alteração
        $this->modal_alterar_motorista(
            $valor->id_Motorista,
            $valor->id_usuario,
            $valor->cnh,
            $valor->categoria,
            $valor->validade_cnh
        );
    }
    ?>




















    <!-- Scripts Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>




</body>

</html>