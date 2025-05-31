<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/CSS/pedidos.css">

    <title>Cadastro de Pedidos</title>
</head>

<body>

<header>
<?= $menu ?>


</header>

    <br><br>    <br><br>


    <div class="container-fluid py-5 px-4">
        <br><br>

        <form method="post" action="index.php" class="row g-4 align-items-end">
            <div class="col-lg-9 col-md-8">
                <label for="numeroPedido" class="form-label">Número do Pedido</label>
                <input type="text" name="numeroPedido" class="form-control" id="numeroPedido" placeholder="Digite o número do pedido...">
            </div>
            <div class="col-lg-3 col-md-4 d-grid">
                <button type="submit" name="consultar_rotas" class="btn btn-primary">
                    <i class="bi bi-search"></i> Consultar Rota
                </button>
            </div>
        </form>

        <div class="container-fluid py-5 px-4">
            <h3 class="mb-4"><i class="bi bi-truck"></i> Rotas Cadastradas</h3>

            <div class="card shadow">
                <div class="card-header text-white text-center" style="background-color: #3e84b0;">
                    Lista de Rotas
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Motorista</th>
                                <th>Veículo</th>
                                <th>Origem</th>
                                <th>Destino</th>
                                <th>Status</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rotas as $rota): ?>
                                <tr>
                                    <td><?= $rota->id_Rotas ?></td>
                                    <td><?= $rota->motorista_nome ?></td>
                                    <td><?= $rota->veiculo_placa ?></td>
                                    <td><?= $rota->origem ?></td>
                                    <td><?= $rota->destino ?></td>
                                    <td><?= $rota->status_rota ?></td>

                                    <td class="text-center">
                                        <button class="btn btn-light btn-sm btn-outline-info"
                                            type="button"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modal_visualizar_rota<?= $rota->id_Rotas ?>">
                                            <i class="bi bi-eye-fill"></i>
                                        </button>
                                        <button class="btn btn-light btn-sm btn-outline-warning"
                                            type="button"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modal_editar_status_rota<?= $rota->id_Rotas ?>">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <a href="index.php?excluir_rota=<?= $rota->id_Rotas ?>" class="btn btn-sm btn-outline-danger" title="Excluir" onclick="return confirm('Deseja realmente excluir esta rota?')">
                                            <i class="bi bi-trash-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modais de Visualização -->
            <?php foreach ($rotas as $rota): ?>
                <div class="modal fade" id="modal_visualizar_rota<?= $rota->id_Rotas ?>" tabindex="-1" aria-labelledby="modalLabel<?= $rota->id_Rotas ?>" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header bg-info text-white">
                                <h5 class="modal-title" id="modalLabel<?= $rota->id_Rotas ?>">Detalhes da Rota #<?= $rota->id_Rotas ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                            </div>
                            <div class="modal-body">
                                <h6><strong>Informações da Rota:</strong></h6>
                                <p><strong>Status:</strong> <?= $rota->status_rota ?></p>
                                <p><strong>Previsão de Entrega:</strong> <?= date('d/m/Y', strtotime($rota->previsao)) ?></p>

                                <hr>

                                <h6><strong>Motorista:</strong></h6>
                                <p><strong>Nome:</strong> <?= $rota->motorista_nome ?></p>
                                <p><strong>CNH:</strong> <?= $rota->motorista_cnh ?? '—' ?></p>

                                <hr>

                                <h6><strong>Veículo:</strong></h6>
                                <p><strong>Placa:</strong> <?= $rota->veiculo_placa ?></p>
                                <p><strong>Modelo:</strong> <?= $rota->veiculo_modelo ?? '—' ?></p>

                                <hr>

                                <h6><strong>Origem e Destino:</strong></h6>
                                <p><strong>Origem:</strong> <?= $rota->origem ?></p>
                                <p><strong>Destino:</strong> <?= $rota->destino ?></p>

                                <hr>

                                <h6><strong>Notas Fiscais:</strong></h6>
                                <?php if (!empty($rota->chave_nota)): ?>
                                    <ul>
                                        <?php foreach (explode(',', $rota->chaves_notas) as $chave): ?>
                                            <li><?= trim($chave) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p class="text-muted">Nenhuma chave informada.</p>
                                <?php endif; ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>


        <!-- Modal Editar Status da Rota -->
        <?php foreach ($rotas as $rota): ?>
    <!-- Modal Editar Status da Rota -->
    <div class="modal fade" id="modal_editar_status_rota<?= $rota->id_Rotas ?>" tabindex="-1" aria-labelledby="editarStatusLabel<?= $rota->id_Rotas ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="index.php">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="editarStatusLabel<?= $rota->id_Rotas ?>">Editar Status da Rota #<?= $rota->id_Rotas ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_rota" value="<?= $rota->id_Rotas ?>">

                        <div class="mb-3">
                            <label for="status_rota<?= $rota->id_Rotas ?>" class="form-label">Status da Rota</label>
                            <select class="form-select" id="status_rota<?= $rota->id_Rotas ?>" name="status_rota" required>
                                <option value="Em preparação" <?= $rota->status_rota === 'Em preparação' ? 'selected' : '' ?>>Em preparação</option>
                                <option value="Saiu do centro de distribuição" <?= $rota->status_rota === 'Saiu do centro de distribuição' ? 'selected' : '' ?>>Saiu do centro de distribuição</option>
                                <option value="Em trânsito" <?= $rota->status_rota === 'Em trânsito' ? 'selected' : '' ?>>Em trânsito</option>
                                <option value="Entregue" <?= $rota->status_rota === 'Entregue' ? 'selected' : '' ?>>Entregue</option>
                                <option value="Cancelado" <?= $rota->status_rota === 'Cancelado' ? 'selected' : '' ?>>Cancelado</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="atualizar_status_rota" class="btn btn-primary">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>




        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>