<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/CSS/veiculos.css">

    <title>Consulta de Veículos</title>
</head>

<body>

    <?= $menu ?>

    <div class="container-fluid py-5 px-4">
        <br><br>

        <div class="d-flex justify-content-end mb-3">
            <button class="btn text-white" style="background-color: #3e84b0;" data-bs-toggle="modal" data-bs-target="#modalInserirVeiculo">
                <i class="bi bi-truck"></i> Adicionar Veículo
            </button>
        </div>

        <div class="card shadow mb-5">
            <div class="card-body">
                <form method="post" action="index.php" class="row g-4 align-items-end">
                    <div class="col-lg-9 col-md-8">
                        <label for="placaVeiculo" class="form-label">Placa do Veículo</label>
                        <input type="text" name="placaVeiculo" class="form-control" id="placaVeiculo" placeholder="Digite a placa...">
                    </div>
                    <div class="col-lg-3 col-md-4 d-grid">
                        <button type="submit" name="consultar_veiculo" class="btn btn-primary">
                            <i class="bi bi-search"></i> Consultar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header text-white text-center" style="background-color: #3e84b0;">
                Lista de Veículos
            </div>

            <div class="card-body">
                <div class="row">
                    <?php foreach ($resultado as $v): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-lg border-0 position-relative">
                                <div class="dropdown position-absolute top-0 end-0 mt-2 me-2">

                                    <button class="btn btn-light btn-sm" type="button" id="dropdownMenuButton<?= $v->id_veiculo ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownMenuButton<?= $v->id_veiculo ?>">
                                        <li>
                                            <button class="dropdown-item text-warning" data-bs-toggle="modal" data-bs-target="#modal_alterar_veiculo<?= $v->id_veiculo ?>">
                                                <i class="bi bi-pencil-square"></i> Editar
                                            </button>

                                        </li>
                                        <li>
                                            <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#excluir_veiculo<?= $v->id_veiculo ?>">
                                                <i class="bi bi-trash"></i> Excluir
                                            </button>
                                        </li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="bi bi-truck fs-3 text-primary me-3"></i>
                                        <h5 class="card-title mb-0"><?= $v->modelo ?> - <?= $v->placa ?></h5>
                                    </div>

                                    <p class="mb-2">
                                        <span class="badge bg-primary p-2 fs-6">
                                            <i class="bi bi-hash"></i> <?= $v->id_veiculo ?>
                                        </span>
                                    </p>

                                    <p class="mb-1"><i class="bi bi-building text-primary"></i> Marca: <?= $v->marca ?></p>
                                    <p class="mb-1"><i class="bi bi-palette text-primary"></i> Cor: <?= $v->cor ?></p>
                                    <p class="mb-1"><i class="bi bi-calendar-check text-primary"></i> Ano: <?= $v->ano ?></p>

                                    <p class="mb-0">
                                        <?php if ($v->status_veiculo == 'Ativo'): ?>
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle-fill"></i> Ativo
                                            </span>
                                        <?php elseif ($v->status_veiculo == 'Manutenção'): ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-wrench-adjustable-circle-fill"></i> Manutenção
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle-fill"></i> Inativo
                                            </span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>




            <!-- Modal Inserir Veículo -->
            <div class="modal fade" id="modalInserirVeiculo" tabindex="-1" aria-labelledby="modalInserirVeiculoLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header text-white" style="background-color: #3e84b0;">
                            <h5 class="modal-title" id="modalInserirVeiculoLabel">
                                <i class="bi bi-truck"></i> Novo Veículo
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>
                        <div class="modal-body">
                            <form action="index.php" method="post">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="placa" class="form-label">Placa</label>
                                        <input type="text" class="form-control" id="placa" name="placa" oninput="this.value = this.value.toUpperCase();" placeholder="Digite a placa do veículo" maxlength="6">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="modelo" class="form-label">Modelo</label>
                                        <input type="text" name="modelo" id="modelo" class="form-control" placeholder="Ex: Strada 1.4" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="marca" class="form-label">Marca</label>
                                        <input type="text" name="marca" id="marca" class="form-control" placeholder="Fiat, Ford, VW..." required>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="ano" class="form-label">Ano</label>
                                        <input type="number" name="ano" id="ano" class="form-control" placeholder="2022" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="cor" class="form-label">Cor</label>
                                        <input type="text" name="cor" id="cor" class="form-control" placeholder="Branco, Prata..." required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="status_veiculo" class="form-label">Status</label>
                                        <select name="status_veiculo" id="status_veiculo" class="form-select" required>
                                            <option value="">Selecione</option>
                                            <option value="Ativo">Ativo</option>
                                            <option value="Manutenção">Manutenção</option>
                                            <option value="Inativo">Inativo</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="observacoes" class="form-label">Observações</label>
                                        <textarea name="observacoes" id="observacoes" class="form-control" rows="3" placeholder="Detalhes adicionais, como última revisão, avarias etc."></textarea>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-3">
                                    <button type="submit" name="cadastroVeiculo" class="btn text-white me-2" style="background-color: #3e84b0;">Salvar</button>
                                    <button type="reset" class="btn btn-secondary">Limpar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <?php //foreach ($resultado as $v) {
            /*$this->modal_excluir_veiculo($veiculo->id_veiculo, $veiculo->placa);
                /*$this->modal_alterar_veiculo($veiculo);*/
            //} 
            ?>

            <!-- Modal Excluir Veículo -->
            <?php foreach ($resultado as $v):
                $this->modal_excluir_veiculo($v->id_veiculo, $v->modelo, $v->placa);
                $this->modal_alterar_veiculo($v->id_veiculo, $v->modelo, $v->placa, $v->marca, $v->cor, $v->ano, $v->status_veiculo);

            ?>
            <?php endforeach; ?>







            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>