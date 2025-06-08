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

    public function delete($condicao) 
    {
        return $this->crud->delete($condicao);
    }
    
}




