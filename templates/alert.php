<div class="modal fade" id="mensagemModal" tabindex="-1" aria-labelledby="mensagemLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-<?= $tipo ?? 'warning' ?> text-white">
        <h5 class="modal-title" id="mensagemLabel">Informação</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-<?= $tipo ?? 'warning' ?>" role="alert">
          <?= htmlspecialchars($mensagem ?? '') ?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-<?= $tipo ?? 'warning' ?>" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
  var mensagemModal = new bootstrap.Modal(document.getElementById("mensagemModal"));
  mensagemModal.show();
});
</script>
