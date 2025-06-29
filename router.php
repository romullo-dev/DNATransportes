<?php
require_once __DIR__ . "/autoload.php";

$url = explode('?', $_SERVER['REQUEST_URI']);
$pagina = $url[1];

#ROTAS DE REDIRECIONAMENTO
//redirecionar para pagina informada
if (isset($pagina)) {
    $objController = new Controller();
    $objController->redirecionar($pagina);
}

if (isset($_GET['motorista'])) {
    $formulario = 'motorista';
    $objController = new Controller();
    $objController->gerar_Motorista($formulario);
}

if (isset($_POST['motorista'])) {

    $controller = new Controller();
    $dados =  [
        'cnh' => $_POST['cnh'],
        'categoria' => $_POST['categoria'],
        'validade_cnh' => $_POST['validade_cnh'],
        'id_usuario' => $_POST['id_usuario'],
    ];

    $controller->inserirMotorista($dados);
}


<<<<<<< HEAD
if(isset($_GET['usuario'])) {
    $ControllerUsuario = new ControllerUsuario();
    $ControllerUsuario->formulario();



}

=======
if (isset($_POST['login'])) {
    $Controller = new ControllerUsuario();
    $dados = [
        'user' => $_POST['user'] ?? '',
        'senha' => $_POST['senha'] ?? '',
    ];

    $Controller->login($dados);
}


if (isset($_POST['cadastroUsuario'])) {

    $Controller =  new ControllerUsuario();

    $nomeFoto = null;

    if (!empty($_FILES['foto']['name'])) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nomeFoto = uniqid('foto_') . '.' . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], 'uploads/' . $nomeFoto);
    }

    $dados = [
        'nome'     => $_POST['nome'] ?? '',
        'cpf'      => $_POST['cpf'] ?? '',
        'user'     => $_POST['user'] ?? '',
        'senha' => password_hash($_POST['senha'], PASSWORD_DEFAULT),
        'telefone' => $_POST['telefone'] ?? '',
        'tipo'  => $_POST['tipo'] ?? '',
        'status'   => $_POST['status'] ?? 'Ativo',
        'email'    => $_POST['email'] ?? '',
        'foto'     => $nomeFoto
    ];

    $Controller->inserir($dados);
}

if (isset($_POST['consultar_usuario'])) {
    $Controller =  new ControllerUsuario();
    $dados = [
        'nome'     => $_POST['nome'] ?? '',
    ];

    $Controller->read($dados);
}

if (isset($_POST['excluir_usuario'])) {
    $Controller = new ControllerUsuario();
    $dados = [
        'id_usuario' => $_POST['id_usuario'] ?? '',
    ];
    $Controller->excluir_usuario($dados);
}


>>>>>>> 96d6fb22c0c7162f4afb1dc2a01da67a6ec7aeb5
