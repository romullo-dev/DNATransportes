<?php

class Conexao
{
    private $host = 'localhost';
    private $database = 'db_sistema_logistica';
    private $username = 'root';
    private $password = ''; 
    private $link = null;


    public function conectar()
    {
        try {
            $pdo = new PDO("mysql:host={$this->host};dbname={$this->database}","{$this->username}","{$this->password}");
           
            //echo"Conectado";
            return  $this->link = $pdo;;
        } catch (PDOException $e) {
            return false; 
        }
    }
}


//$conexao = new Conexao();
//$conexao->conectar();
