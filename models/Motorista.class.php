<?php

include_once("Conexao.class.php");
include_once("Crud.class.php");

class Motorista 
{
    private $db;
    private $crud;

    public function __construct()
    {
        $this->db = (new Conexao ())->conectar();
        $this->crud = new Crud(
            $this->db,
            'motorista'
        );
    }

    public function Adicionar ($dados)
    {
        return $this->crud->inserir($dados);
    }

    public function delete ($condicao)
    {
        return $this->crud->delete($condicao);
    }

    public function gerar_motorista ($formulario)
    {
        return $this->crud->gerarFormularioInserir($formulario);
    }
}



