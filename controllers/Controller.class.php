<?php

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

}