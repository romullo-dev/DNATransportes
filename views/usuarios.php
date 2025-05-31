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

    <div class="container-fluid py-5 px-4">
    <BR></BR>     <br>


        <div class="d-flex justify-content-end mb-3">
            <button class="btn text-white" style="background-color: #3e84b0;" data-bs-toggle="modal" data-bs-target="#modalInserirUsuario">
                <i class="bi bi-person-plus"></i> Adicionar Usuário
            </button>
        </div>
        <BR>

        <div class="card shadow mb-5">
            <div class="card-body">
                <form method="post" action="index.php" class="row g-4 align-items-end">
                    <div class="col-lg-9 col-md-8">
                        <label for="nomeUsuario" class="form-label">Nome do Usuário</label>
                        <input type="text" name="nomeUsuario" class="form-control" id="nomeUsuario" placeholder="Digite o nome do usuário...">
                    </div>
                    <div class="col-lg-3 col-md-4 d-grid">
                        <button type="submit" name="consultar_usuario" class="btn btn-primary">
                            <i class="bi bi-search"></i> Consultar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header text-white text-center" style="background-color: #3e84b0;">
                Lista de Usuários
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach ($resultado as $valor): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm border-0 position-relative">
                                <div class="dropdown position-absolute top-0 end-0 mt-2 me-2">
                                    <button class="btn btn-light btn-sm" type="button" id="dropdownMenuButton<?= $valor->id_usuario ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownMenuButton<?= $valor->id_usuario ?>">
                                        <li>
                                            <button class="dropdown-item text-warning" data-bs-toggle="modal" data-bs-target="#modal_alterar_usuario<?= $valor->id_usuario ?>">
                                                <i class="bi bi-pencil-square"></i> Editar
                                            </button>

                                        </li>
                                        <li>
                                            <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#excluir_usuario<?= $valor->id_usuario ?>">
                                                <i class="bi bi-trash"></i> Excluir
                                            </button>
                                        </li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="uploads/<?= $valor->foto ?>"
                                            onerror="this.src='assets/img/default.png'"
                                            class="rounded-circle border"
                                            style="width: 70px; height: 70px; object-fit: cover;"
                                            alt="Foto de <?= $valor->nomeCompleto ?>">
                                        <div class="ms-3">
                                            <h5 class="mb-0"><?= $valor->nomeCompleto ?></h5>
                                            <small class="text-muted">@<?= $valor->user ?></small>
                                        </div>
                                    </div>

                                    <p class="mb-1"><i class="bi bi-envelope text-primary"></i> <?= $valor->email ?></p>
                                    <p class="mb-1"><i class="bi bi-phone text-primary"></i> <?= $valor->telefone ?></p>
                                    <p class="mb-1"><i class="bi bi-person-badge text-primary"></i> <?= $valor->id_tipo ?></p>
                                    <p class="mb-1">
                                        <i class="bi bi-activity text-primary"></i>
                                        <?php if ($valor->id_status_func === 'Ativo'): ?>
                                            <span class="badge bg-success">Ativo</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inativo</span>
                                        <?php endif; ?>
                                    </p>
                                    <p class="mb-0"><i class="bi bi-credit-card-2-front text-primary"></i> CPF: <?= $valor->cpf ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>





    </div>

    <!-- Modal Inserir Usuário -->
    <div class="modal fade" id="modalInserirUsuario" tabindex="-1" aria-labelledby="modalInserirUsuario" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-white" style="background-color: #3e84b0;">
                    <h5 class="modal-title" id="modalInserirUsuarioLabel">
                        <i class="bi bi-person-plus-fill"></i> Novo Usuário
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <main>
                        <div class="container mt-4">
                            <form action="index.php" method="post" enctype="multipart/form-data" id="funcionarioForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nome" class="form-label">Nome Completo</label>
                                        <input type="text" id="nomeCompleto" name="nomeCompleto" class="form-control" placeholder="Ex.: Maria Oliveira" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="cpf" class="form-label">CPF</label>
                                        <input type="text" id="cpf" name="cpf" class="form-control" placeholder="Ex.: 123.456.789-00" maxlength="11" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="nome" class="form-label">User</label>
                                        <input type="text" name="user" id="user" class="form-control" placeholder="user" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="senha" class="form-label">Senha</label>
                                        <input type="text" id="senha" name="senha" class="form-control" placeholder="Ex.: 1234" maxlength="14" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Telefone</label>
                                        <input type="tel" id="telefone" name="telefone" class="form-control" placeholder="Ex.: (11) 91234-5678" maxlength="15" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="cargo" class="form-label">Tipo</label>
                                        <select id="id_tipo" name="id_tipo" class="form-select" required>
                                            <option value="">Selecione...</option>
                                            <option value="Motorista">Motorista</option>
                                            <option value="ADM">ADM</option>
                                            <option value="Usuario">Usuário</option>
                                            <option value="Cliente">Cliente</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="id_status_func" class="form-label">Status do Funcionário</label>
                                        <select id="id_status_func" name="id_status_func" class="form-select" required>
                                            <option value="">Selecione...</option>
                                            <option value="Ativo">Ativo</option>
                                            <option value="Inativo">Inativo</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" id="email" name="email" class="form-control" placeholder="Ex.: teste@teste.com" maxlength="255" required>
                                    </div>

                                    <!-- Campo de Upload de Foto -->
                                    <div class="col-md-6 mb-3">
                                        <label for="foto" class="form-label">Foto do Funcionário</label>
                                        <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" name="cadastroUsuario" class="btn btn-success" style="background-color: #3e84b0;">Cadastrar Funcionário</button>
                                    <button type="reset" class="btn btn-danger">Limpar Campos</button>
                                </div>
                            </form>
                        </div>
                    </main>

                </div>
            </div>
        </div>
    </div>

    <?php
    foreach ($resultado as $usuario) {
        // Modal de exclusão
        $this->modal_excluir_usuario($usuario->id_usuario, $usuario->nomeCompleto);

        // Modal de alteração
        $this->modal_alterar_usuario(
            $usuario->id_usuario,
            $usuario->nomeCompleto,
            $usuario->cpf,
            $usuario->user,
            $usuario->senha,
            $usuario->telefone,
            $usuario->email,
            $usuario->id_tipo,
            $usuario->id_status_func
        );
    }
    ?>




















    <!-- Scripts Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>