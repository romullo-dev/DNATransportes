<?php
include_once("../models/Motorista.class.php");
class ControllerUsuario
{   
    public function formulario()
    {
        $usuario = new usuario();
         $dados = $usuario->consulta();
        include_once("../views/Usuario.php");

    }

    

}

