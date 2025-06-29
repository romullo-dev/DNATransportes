<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mundo DNA - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/CSS/login.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row h-100">
            <div class="col-lg-8 left-panel"></div>
            <div class="col-lg-4 right-panel">
                <div class="login-box">
                    <h2>DNA Transportes</h2>
                    <form method="post" action="index.php">
                        <div class="mb-3">
                            <label for="login" class="form-label">Login</label>
                            <input type="text" id="login" class="form-control" placeholder="Digite seu login" name="user">
                        </div>
                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" id="senha" class="form-control" placeholder="Digite sua senha" name="senha">
                        </div>
                        <div class="recaptcha-box">
                            <input type="checkbox" id="recaptcha">
                            <label for="recaptcha" class="form-check-label">Lembrar</label>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary w-100 mt-3">Acessar</button>
                        <a href="#" class="d-block text-center mt-2 text-white">Esqueci Minha Senha</a>
                    </form>
                    <footer>&copy; 2024 Entrega - Todos os direitos reservados.</footer>
                </div>
            </div>
        </div>
        <div>
        </div>
    </div>

    <!-- Scripts do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>