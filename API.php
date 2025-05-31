<?php
//definir o cabecalho como arquivo json
header("Content-Type: application/json");

//não mostrar erros
error_reporting(~E_ALL & ~E_NOTICE & ~E_WARNING);

// Define um token estático para exemplo
define('API_TOKEN', '781e5e245d69b566979b86e28d23f2c7');

// Função para verificar se o token enviado é válido
function verificarToken($headers)
{
    if (! isset($headers['Authorization'])) {
        return false;
    }

    // O formato esperado é: "Authorization: Bearer TOKEN"
    $authHeader = $headers['Authorization'];
    if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $token = $matches[1];
        return $token === API_TOKEN;
    }

    return false;
}

// Captura os headers da requisição
$headers = getallheaders();

// Verifica o token
if (! verificarToken($headers)) {
    http_response_code(401);
    print json_encode(['erro' => 'Token inválido ou ausente.']);
    exit;
}

include_once 'autoload.php';

$method = $_SERVER['REQUEST_METHOD'];

$input = json_decode(file_get_contents('php://input'), true);


switch ($method) {
    case 'GET':
        break;

    case 'POST':
        break;

    case 'PUT':
        # code...
        break;

    case 'DELETE':
        # code...
        break;

    default:
        print "METODO NÃO ENCONTRADO!";
        break;
}
