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

    public function update ($dados, $condicao)
    {
        return $this->crud->update($dados, $condicao);
    }

    public function read ($condicao)
    {
        return $this->crud->read($condicao);
    }

    public function gerarFormulario($formulario)
    {
        return $this->crud->gerarFormularioInserir($formulario);
    }

     public function consulta()
    {
        return $this->crud->consulta();
    }


    
}








