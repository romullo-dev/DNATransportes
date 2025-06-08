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
    $objController = new Controller();
    $objController->gerar_Motorista();
}
