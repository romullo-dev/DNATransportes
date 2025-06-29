<?php
// Recebe as variáveis: $idModal, $titulo, $mensagem, $inputsHidden (array), $nomeBotao, $classeBotao (opcional)

$classeBotao = $classeBotao ?? 'btn-danger';

$inputsHtml = '';
foreach ($inputsHidden as $name => $value) {
    $inputsHtml .= "<input type='hidden' name='{$name}' value='{$value}'>";
}
?>

<div class="modal fade" id="<?= $idModal ?>" tabindex="-1" aria-labelledby="<?= $idModal ?>Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="<?= $idModal ?>Label"><?= $titulo ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <?= $mensagem ?>
            </div>
            <form method="post" action="index.php">
                <div class="modal-footer">
                    <?= $inputsHtml ?>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="excluir_usuario" class="btn <?= $classeBotao ?>"><?= $nomeBotao ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
