<?php
class Crud
{
    private $db;
    private $tabela;
    private $alias;
    public function __construct(PDO $db, $tabela, $alias = '')
    {
        $this->db = $db;
        $this->tabela = $tabela;
        $this->alias =  $alias ?:  $tabela;
    }
    public function inserir($dados)
    {
        $campos = implode(", ", array_keys($dados));
        $placeholders = ":" . implode(", :", array_keys($dados));
        $sql = "INSERT INTO {$this->tabela} ({$campos})  VALUES ({$placeholders})";
        $query = $this->db->prepare($sql);
        foreach ($dados as $campo => $value) {
            $query->bindValue(":{$campo}", $value);
        }
        return $query->execute();
    }

    public function delete($condicao)
    {
        $where = implode(" AND ", array_map(function ($campo) {
            return "{$campo} = :{$campo}";
        }, array_keys($condicao)));

        $sql = "DELETE FROM {$this->tabela} WHERE {$where}";

        $query = $this->db->prepare($sql);

        foreach ($condicao as $campo => $value) {
            $query->bindValue(":{$campo}", $value);
        }

        return $query->execute();
    }
    public function update($condicao, $dados)
    {
        $set = implode(", ", array_map(function ($campo) {
            return "{$campo} = :{$campo}";
        }, array_keys($dados)));
        $where = implode(" AND ", array_map(function ($campo) {
            return "{$campo} = :cond_{$campo}";
        }, array_keys($condicao)));
        $sql = "UPDATE {$this->tabela} SET {$set} WHERE {$where}";
        $query = $this->db->prepare($sql);
        foreach ($dados as $campo => $value) {
            $query->bindValue(":{$campo}", $value);
        }
        foreach ($condicao as $campo => $value) {
            $query->bindValue(":cond_{$campo}", $value);
        }

        return $query->execute();
    }

    public function read($joins = [], $condicao = [])
    {
        $sql = "SELECT * FROM {$this->tabela}";
        foreach ($joins as $join) {
            $sql .= "{$join['tipo']} JOIN {$join['tabela']} ON {$join['condicao']}";
        }
        if (!empty($condicao)) {
            $where = implode(" AND ", array_map(function ($campo) {
                return "{$campo} = :{$campo}";
            }, array_keys($condicao)));

            $sql .= " WHERE {$where}";
        }
        $query = $this->db->prepare($sql);

        foreach ($condicao as $campo => $value) {
            $query->bindValue(":{$campo}", $value);
        }
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
