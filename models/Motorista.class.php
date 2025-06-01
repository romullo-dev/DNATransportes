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
}

$motorista = new Motorista(
);

$dados = [
          'cnh' => '12345678900',
        'categoria' => 'B',
        'validade_cnh' => '2027-05-10',
        'id_Usuario' => 1
];

$motorista->Adicionar ($dados);