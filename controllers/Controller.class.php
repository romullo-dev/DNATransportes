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

    public function gerar_Motorista ()
    {
        $moto = new Motorista();
        echo $moto->gerar_motorista();
        //require_once 'views/Motorista.php';
    }

}

