<?php
include_once("../models/Motorista.class.php");
class Controller
{
    public function redirecionar($pagina)
    {
        //iniciar sessao
        session_start();
        //incluir menu
        //incluir a view
        require_once 'views/' . $pagina . '.php';
    }

    public function gerar_Motorista ($formulario)
    {
        $moto = new Motorista();
        echo $moto->gerar_motorista($formulario);
        //require_once 'views/Motorista.php';
    }

    public function inserirMotorista ($dados)
    {
        $moto = new Motorista();
        $moto->Adicionar($dados);
    }

}

