<?php
require_once __DIR__ . '/../models/Conexao.class.php';

$conexao = new Conexao();
$conn = $conexao->conectar();

// Consulta Motoristas
$stmt = $conn->prepare("SELECT m.id_Motorista, u.nomeCompleto 
                        FROM tb_motorista AS m
                        INNER JOIN tb_usuario AS u ON m.id_usuario = u.id_usuario");
$stmt->execute();
$motoristas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consulta Veículos
try {
    $stmtVeiculos = $conn->prepare("SELECT id_veiculo, placa FROM tb_veiculo");
    $stmtVeiculos->execute();
    $veiculos = $stmtVeiculos->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $veiculos = [];
}


// Consulta CD
try {
    $stmtCds = $conn->prepare("SELECT id_cd, nome_cd, estado_uf FROM cd");
    $stmtCds->execute();
    $cds = $stmtCds->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $cds = [];
}


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro de Rotas | Sistema Logística</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            border: none;
        }
    </style>
</head>

<body>
    <br><br>
    <div class="container mt-5">
        <div class="card shadow rounded-4">
            <div class="card-header  text-white d-flex justify-content-between align-items-center" style="background-color: #3e84b0;">
                <h5 class="mb-0"><i class="bi bi-map-fill me-2"></i>Cadastrar Nova Rota</h5>
            </div>
            <div class="card-body">
                <!-- SELECTOR -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="tipo_rota" class="form-label">
                            <i class="bi bi-signpost-split-fill me-1"></i>Tipo da Rota
                        </label>
                        <select class="form-select" id="tipo_rota" name="status_rota" required>
                            <option value="" selected disabled>Selecione o tipo</option>
                            <option value="coleta">Coleta</option>
                            <option value="transferencia">Transferência</option>
                            <option value="distribuicao">Distribuição</option>
                            <option value="transbordo">Transbordo</option>
                            <option value="devolucao">Devolução</option>
                            <option value="dedicada">Dedicada</option>
                        </select>
                    </div>
                </div>

                <!-- FORM TRANSFERÊNCIA -->
                <div id="form_transferencia" class="formulario" style="display: none;">
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nome_rota" class="form-label"><i class="bi bi-flag-fill me-1"></i>Nome da Rota</label>
                                <input type="text" class="form-control" id="nome_rota" name="nome_rota" required>
                            </div>
                            <div class="col-md-3">
                                <label for="cd_origem" class="form-label">
                                    <i class="bi bi-geo-alt-fill me-1"></i>Origem
                                </label>
                                <select class="form-select" id="cd_origem" name="cd_origem" required>
                                    <option value="" selected disabled>Selecione um Centro de Distribuição</option>
                                    <?php foreach ($cds as $cd): ?>
                                        <option value="<?= $cd['id_cd'] ?>">
                                            <?= htmlspecialchars($cd['nome_cd']) ?> - <?= htmlspecialchars($cd['estado_uf']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                            <div class="col-md-3">
                                <label for="destino" class="form-label"><i class="bi bi-geo-fill me-1"></i>Destino</label>
                                        <select class="form-select" id="cd_origem" name="cd_origem" required>
                                    <option value="" selected disabled>Selecione um Centro de Distribuição</option>
                                    <?php foreach ($cds as $cd): ?>
                                        <option value="<?= $cd['id_cd'] ?>">
                                            <?= htmlspecialchars($cd['nome_cd']) ?> - <?= htmlspecialchars($cd['estado_uf']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="distancia" class="form-label"><i class="bi bi-rulers me-1"></i>Distância (km)</label>
                                <input type="number" class="form-control" id="distancia" name="distancia" required>
                                <!--Vai ser algo altomatico-->
                            </div>
                            <div class="col-md-4">
                                <label for="previsao" class="form-label"><i class="bi bi-clock-history me-1"></i>Previsão de Tempo</label>
                                <input type="date" class="form-control" id="previsao" name="previsao" required>
                                <!--Vai ser algo altomatico-->
                            </div>
                            <div class="col-md-4">
                                <label for="data_saida" class="form-label"><i class="bi bi-calendar-event me-1"></i>Data de Saída</label>
                                <input type="date" class="form-control" id="data_saida" name="data_saida" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="motorista_id" class="form-label"><i class="bi bi-person-badge-fill me-1"></i>Motorista</label>
                                <select class="form-select" id="motorista_id" name="motorista_id" required>
                                    <option value="" selected disabled>Selecione um motorista</option>
                                    <?php foreach ($motoristas as $motorista): ?>
                                        <option value="<?= $motorista['id_Motorista'] ?>">
                                            <?= htmlspecialchars($motorista['nomeCompleto']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="veiculo_id" class="form-label"><i class="bi bi-truck-front-fill me-1"></i>Veículo</label>
                                <select class="form-select" id="veiculo_id" name="veiculo_id" required>
                                    <option value="" selected disabled>Selecione um veículo</option>
                                    <?php foreach ($veiculos as $veiculo): ?>
                                        <option value="<?= $veiculo['id_veiculo'] ?>">
                                            <?= htmlspecialchars($veiculo['placa']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>


                        <!--<input type="hidden" name="status_rota" value="Em trânsito">-->

                        <div class="mb-3">
                            <label for="observacoes" class="form-label"><i class="bi bi-chat-left-text-fill me-1"></i>Observações</label>
                            <textarea class="form-control" id="observacoes" name="observacoes" rows="3" placeholder="Digite informações adicionais sobre a rota (opcional)"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="chaves_nfe" class="form-label">
                                <i class="bi bi-file-earmark-text me-1"></i>Chaves das Notas Fiscais (uma por linha)
                            </label>
                            <textarea class="form-control" id="chaves_nfe" name="chaves_nfe" rows="6" placeholder="Digite cada chave com 44 dígitos, uma por linha..." style="resize: vertical;"></textarea>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" name="inserirRotas" class="btn btn-success">
                                <i class="bi bi-check2-circle me-1"></i>Salvar Rota
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>





        <!-- Bootstrap JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- SCRIPT MÁGICO -->
        <script>
            document.getElementById('tipo_rota').addEventListener('change', function() {
                const tipoSelecionado = this.value;
                const formularios = document.querySelectorAll('.formulario');

                formularios.forEach(form => {
                    form.style.display = 'none';
                });

                const formAtivo = document.getElementById('form_' + tipoSelecionado);
                if (formAtivo) {
                    formAtivo.style.display = 'block';
                }
            });
        </script>
</body>

</html>