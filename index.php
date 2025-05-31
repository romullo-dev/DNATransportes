
<?php
//iniciar sessao

//não mostrar ERROS 
error_reporting(~E_ALL & ~E_NOTICE & ~E_WARNING);


// Ativa o carregamento automático das classes
require_once 'autoload.php';

// Inicia a sessão (caso o sistema use autenticação)
session_start();

// Define um roteador básico para direcionar páginas
require_once 'router.php';
