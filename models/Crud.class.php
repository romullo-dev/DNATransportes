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
        $this->alias = $alias ?: $tabela;
    }

    public function inserir($dados)
    {
        try {
            $campos = implode(", ", array_keys($dados));
            $placeholders = ":" . implode(", :", array_keys($dados));
            $sql = "INSERT INTO {$this->tabela} ({$campos}) VALUES ({$placeholders})";
            $query = $this->db->prepare($sql);

            foreach ($dados as $campo => $value) {
                $query->bindValue(":{$campo}", $value);
            }

            return $query->execute();
        } catch (PDOException $e) {
            echo "Erro ao inserir: " . $e->getMessage();
            return false;
        }
    }

    public function delete($condicao)
    {
<<<<<<< HEAD
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
=======
        try {
>>>>>>> 96d6fb22c0c7162f4afb1dc2a01da67a6ec7aeb5
            $where = implode(" AND ", array_map(function ($campo) {
                return "{$campo} = :{$campo}";
            }, array_keys($condicao)));

            $sql = "DELETE FROM {$this->tabela} WHERE {$where}";
            $query = $this->db->prepare($sql);

            foreach ($condicao as $campo => $value) {
                $query->bindValue(":{$campo}", $value);
            }
            $query->execute();
            return true;
        } catch (PDOException $e) {
            echo "Erro ao deletar: " . $e->getMessage();
            return false;
        }
    }

    public function update($condicao, $dados)
    {
        try {
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
        } catch (PDOException $e) {
            echo "Erro ao atualizar: " . $e->getMessage();
            return false;
        }
    }

    public function read($joins = [], $condicao = [])
    {
        try {
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
            return $query->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo "Erro ao ler dados: " . $e->getMessage();
            return false;
        }
    }
    public function readLike(array $filtros = [], $orderBy = null)
    {
        try {
            $sql = "SELECT * FROM {$this->tabela} WHERE 1=1";

            foreach ($filtros as $campo => $valor) {
                $sql .= " AND {$campo} LIKE :{$campo}";
            }

            if ($orderBy) {
                $sql .= " ORDER BY {$orderBy} DESC";
            }

            $query = $this->db->prepare($sql);

            foreach ($filtros as $campo => $valor) {
                $query->bindValue(":{$campo}", "%" . $valor . "%", PDO::PARAM_STR);
            }

            $query->execute();
            return $query->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo "Erro ao ler com filtro: " . $e->getMessage();
            return false;
        }
    }



    public function gerarFormularioInserir($formulario)
    {
        try {
            $form = $formulario;
            $sql = "DESCRIBE {$this->tabela}";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $campos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $form = '<form method="POST" enctype="multipart/form-data">' . PHP_EOL;

            foreach ($campos as $campo) {
                $nomeCampo = $campo['Field'];
                $tipoCampo = $campo['Type'];

                if (strpos($campo['Extra'], 'auto_increment') !== false) {
                    continue;
                }

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
        } catch (PDOException $e) {
            echo "Erro ao gerar formulário: " . $e->getMessage();
            return '';
        }
    }

<<<<<<< HEAD
        $form .= "<button type=\"submit\" class=\"btn btn-primary\" name=\"{$formulario}\">Salvar</button>" . PHP_EOL;
        $form .= '</form>';
=======
    public function login(array $dados)
    {
        try {
            $sql = "SELECT * FROM {$this->tabela} WHERE user = :user LIMIT 1";
            $query = $this->db->prepare($sql);
            $query->bindValue(":user", $dados['user'] ?? '', PDO::PARAM_STR);
            $query->execute();
>>>>>>> 96d6fb22c0c7162f4afb1dc2a01da67a6ec7aeb5

            $usuario = $query->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($dados['senha'] ?? '', $usuario['senha'])) {
                return true;
            }

            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function consulta()
    {
        $sql = "DESCRIBE {$this->tabela}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $campos = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}


