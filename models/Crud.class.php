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

    public function delete ($condicao)
    {
        $where = implode (" AND ", array_map( function ($campo){
            return "{$campo} = :{$campo}";
        }, array_keys( $condicao )));

        $sql = "DELETE FROM {$this->tabela} WHERE {$where}";

        $query = $this->db->prepare($sql);

        foreach ($condicao as $campo => $value) {
            $query->bindValue(":{$campo}", $value );
        }

        return $query->execute();
    }
}
$usuario = new usuario();
$usuario->inserir([
    'nome_usuario' => 'Carlos Silva',
    'user' => 'carlos',
    'senha' => '12345',
    'tipo_usuario' => 'Motorista',
    'cpf' => '12345678901',
    'status_funcionario' => 'Ativo',
    'email' => 'carlos@email.com',
    'foto' => 'foto.png'
]);

