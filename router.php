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

if(isset($_GET['motorista'])) {
    $formulario = 'motorista';
    $objController = new Controller();
    $objController->gerar_Motorista($formulario);
}

if(isset($_POST['motorista'])) {
    
    $controller = new Controller();
    $dados =  [
        'cnh'=> $_POST['cnh'],
        'categoria'=> $_POST['categoria'],
        'validade_cnh'=> $_POST['validade_cnh'],
        'id_Usuario'=> $_POST['id_Usuario'],
    ];

    $controller->inserirMotorista($dados);
}


if(isset($_GET['usuario'])) {
    $ControllerUsuario = new ControllerUsuario();
    $ControllerUsuario->formulario();



}

