<?php
require_once "Conexao.class.php";
require_once "Crud.class.php";

class  usuario  
{
    private $db;
    private $crud;

    public function __construct( )
    {
        $this->db = (new Conexao ()) ->conectar();
        $this->crud = new Crud($this->db, 'usuario') ;
    }

    
    public function inserir ($dados)
    {
        return $this->crud->inserir($dados);
    }
    
}


$usuario = new usuario();

$dados = [
    'nome_usuario' => 'Carlos Oliveira',
    'user' => 'carlos.oliveira3',
    'senha' => 'senha123',
    'tipo_usuario' => 'Motorista',
    'cpf' => '12345678404',
    'status_funcionario' => 'Ativo',
    'email' => 'carlos@example.com',
    'foto' => 'fotos/carlos.jpg'
];

$usuario->inserir($dados);


