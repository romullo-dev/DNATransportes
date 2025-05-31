<!doctype html>
<html lang="pt-br">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous" />
</head>


<!-- CNPJ ou CPF do remetente -->
<!-- rezação sozial ou nome do remetente -->
<!-- CEP do remetente -->
<!-- endereco do remetente -->
<!-- numero da casa do remetente -->

<!-- numero do pedido -->
<!-- numero da nota -->
<!-- Bipagem de nota -->

<!-- CNPJ ou CPF do destinatario -->
<!-- rezão sozial ou nome do destinatario -->
<!-- CEP do destinatario -->
<!-- endereco do destinatario -->
<!-- numero da casa do destinatario -->


<!-- anexa documento -->

<br><br>

<div class="container mt-4">
    <div class="card shadow-sm rounded-4">
        <div class="card-header   text-white rounded-top-4" style="background-color: #3e84b0;">
            <h5 class="mb-0"><i class="bi bi-clipboard-data me-2"></i>Formulário de Cotação</h5>
        </div>
        <div class="card-body">
            <form action="index.php" method="POST" enctype="multipart/form-data">
                <!-- Remetente -->
                
                <!-- Anexar Documento PDF -->
                <h6 class="mt-4"><i class="bi bi-paperclip"></i> Anexar Documento PDF / XML</h6>

                <div class="row mb-3">

                    <div class="col-md-3">
                        <input class="form-control" type="file" name="arquivo_nome" accept="application/pdf" >
                    </div>

                    <div class="col-md-3">
                        <input class="form-control" type="file" name="arquivo_xml" accept=".xml" required>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" name="incluir_pedido" class="btn btn-success px-4">
                        <i class="bi bi-send-fill me-1"></i> Enviar Cotação
                    </button>
                </div>

                <input type="hidden" name="status_pedido" value="Em preparação">



            </form>
        </div>
    </div>
</div>







<!-- Bootstrap JavaScript Libraries -->
<script
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
    crossorigin="anonymous"></script>
</body>

</html>