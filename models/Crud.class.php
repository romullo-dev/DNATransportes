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

    public function gerarFormularioInserir($formulario)
    {
        $form = $formulario;
        $sql = "DESCRIBE {$this->tabela}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $campos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $form = '<form method="POST" enctype="multipart/form-data">' . PHP_EOL;

        foreach ($campos as $campo) {
            $nomeCampo = $campo['Field'];
            $tipoCampo = $campo['Type'];

            // Ignora campo auto_increment (ex: id_usuario)
            if (strpos($campo['Extra'], 'auto_increment') !== false) {
                continue;
            }

            // Detectar tipo
            $tipoInput = 'text';
            if (str_contains($tipoCampo, 'int')) $tipoInput = 'number';
            elseif (str_contains($tipoCampo, 'date')) $tipoInput = 'date';
            elseif (str_contains($tipoCampo, 'text')) $tipoInput = 'textarea';
            elseif (str_contains($nomeCampo, 'senha')) $tipoInput = 'password';
            elseif (str_contains($nomeCampo, 'email')) $tipoInput = 'email';
            elseif (str_contains($nomeCampo, 'foto')) $tipoInput = 'file';

            $label = ucwords(str_replace('_', ' ', $nomeCampo));

            $form .= '<div class="mb-3">' . PHP_EOL;
            $form .= "<label for=\"{$nomeCampo}\" class=\"form-label\">{$label}</label>" . PHP_EOL;

            if ($tipoInput === 'textarea') {
                $form .= "<textarea name=\"{$nomeCampo}\" id=\"{$nomeCampo}\" class=\"form-control\"></textarea>" . PHP_EOL;
            } else {
                $form .= "<input type=\"{$tipoInput}\" name=\"{$nomeCampo}\" id=\"{$nomeCampo}\" class=\"form-control\">" . PHP_EOL;
            }

            $form .= '</div>' . PHP_EOL;
        }

        $form .= "<button type=\"submit\" class=\"btn btn-primary\" name=\"{$formulario}\">Salvar</button>" . PHP_EOL;
        $form .= '</form>';

        return $form;
    }

    public function consulta()
    {
        $sql = "DESCRIBE {$this->tabela}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $campos = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}


