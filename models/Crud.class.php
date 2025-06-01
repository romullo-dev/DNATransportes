<?php 
class Crud
{
    private $db;
    private $tabela;
    private $alias;
    public function __construct(PDO $db, $tabela, $alias = '' )
    {
        $this->db = $db;
        $this->tabela = $tabela;
        $this->alias =  $alias ?:  $tabela;
    }
    public function inserir ($dados)
    {
        $campos = implode(", ", array_keys($dados));
        $placeholders = ":" . implode(", :", array_keys ($dados));
        $sql = "INSERT INTO {$this->tabela} ({$campos})  VALUES ({$placeholders})";
        $query = $this->db->prepare($sql);
        foreach ($dados as $campo => $value) {
            $query->bindValue(":{$campo}", $value );
        }
        return $query->execute();
    }
}

