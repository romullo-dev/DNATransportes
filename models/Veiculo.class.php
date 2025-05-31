<?php

require_once __DIR__ . "/Conexao.class.php";


class Veiculo extends Conexao
{
    private $placa;
    private $modelo;
    private $ano;
    private $marca;
    private $cor;
    private $status_veiculo;
    private $observacoes;
    private $id_veiculo;


    public function getPlaca()
    {
        return $this->placa;
    }

    public function setPlaca($placa): self
    {
        $this->placa = $placa;

        return $this;
    }

    public function getModelo()
    {
        return $this->modelo;
    }

    public function setModelo($modelo): self
    {
        $this->modelo = $modelo;

        return $this;
    }

    public function getAno()
    {
        return $this->ano;
    }

    public function setAno($ano): self
    {
        $this->ano = $ano;

        return $this;
    }

    public function getMarca()
    {
        return $this->marca;
    }

    public function setMarca($marca): self
    {
        $this->marca = $marca;

        return $this;
    }

    public function getCor()
    {
        return $this->cor;
    }

    public function setCor($cor): self
    {
        $this->cor = $cor;

        return $this;
    }

    public function getStatusVeiculo()
    {
        return $this->status_veiculo;
    }

    public function setStatusVeiculo($status_veiculo): self
    {
        $this->status_veiculo = $status_veiculo;

        return $this;
    }

    public function getObservacoes()
    {
        return $this->observacoes;
    }

    public function setObservacoes($observacoes): self
    {
        $this->observacoes = $observacoes;

        return $this;
    }

    public function getIdVeiculo()
    {
        return $this->id_veiculo;
    }

    public function setIdVeiculo($id_veiculo): self
    {
        $this->id_veiculo = $id_veiculo;

        return $this;
    }


    //inserir veiculo
    public function InserirVeiculo($placa, $modelo, $ano, $marca, $cor, $status_veiculo, $observacoes)
    {
        $this->setPlaca($placa);
        $this->setModelo($modelo);
        $this->setAno($ano);
        $this->setMarca($marca);
        $this->setCor($cor);
        $this->setStatusVeiculo($status_veiculo);
        $this->setObservacoes($observacoes);

        $sql = "INSERT INTO tb_veiculo
        (placa,   modelo, ano,  marca,  cor,  status_veiculo,   observacoes )
        VALUES
        (:placa, :modelo, :ano, :marca, :cor, :status_veiculo, :observacoes )";

        try {
            $db = $this->conectar();
            $query = $db->prepare($sql);

            $query->bindValue(":placa", $placa, PDO::PARAM_STR);
            $query->bindValue(":modelo", $modelo, PDO::PARAM_STR);
            $query->bindValue(":ano", $ano, PDO::PARAM_STR);
            $query->bindValue(":marca", $marca, PDO::PARAM_STR);
            $query->bindValue(":cor", $cor,  PDO::PARAM_STR);
            $query->bindValue(":status_veiculo", $status_veiculo, PDO::PARAM_STR);
            $query->bindValue(":observacoes", $observacoes,  type: PDO::PARAM_STR);

            $query->execute();

            return true;
        } catch (Exception $e) {
            //echo 'Erro ao inserir'.$e->getMessage();
            return false;
        }
    }


    public function mostrarVeiculo($placa = null)
    {
        $this->setPlaca($placa);

        $sql = "SELECT * FROM tb_veiculo WHERE 1=1";

        if (!empty($placa)) {
            $sql .= " AND placa LIKE :placa";
        }

        $sql .= " ORDER BY placa";

        try {
            $db = $this->conectar();
            $query = $db->prepare($sql);
            if (!empty($placa)) {
                $Busca = "%" . $placa . "%";
                $query->bindValue(':placa', $Busca, PDO::PARAM_STR);
            }

            $query->execute();
            return $query->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            //echo"Erro ao consultar". $e->getMessage() ."";
            return false;
        }
    }

    //excluir 

    public function excluir_veiculo($id_veiculo)
    {
        $this->setIdVeiculo($id_veiculo);

        $sql = "DELETE FROM tb_veiculo WHERE id_veiculo = :id_veiculo";

        try {
            $db = $this->conectar();
            $query = $db->prepare($sql);
            $query->bindValue(':id_veiculo', $this->getIdVeiculo(), PDO::PARAM_INT);
            $query->execute();
            if ($query->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print "Erro ao excluir: " . $e->getMessage();
            return false;
        }
    }

    // Método alterar veículo
    public function alterar_veiculo($modelo, $marca, $cor, $ano, $status_veiculo, $id_veiculo)
    {
        // Setando os valores
        $this->setModelo($modelo);
        $this->setMarca($marca);
        $this->setCor($cor);
        $this->setAno($ano);
        $this->setStatusVeiculo($status_veiculo);
        $this->setIdVeiculo($id_veiculo);

        // SQL para atualizar veículo
        $sql = "UPDATE tb_veiculo SET 
            modelo = :modelo,
            marca = :marca,
            cor = :cor,
            ano = :ano,
            status_veiculo = :status_veiculo
            WHERE id_veiculo = :id_veiculo";
        try {
            // Conectar ao banco
            $db = $this->conectar();
            // Preparar o SQL
            $query = $db->prepare($sql);

            // Blindar valores
            $query->bindValue(":modelo", $modelo, PDO::PARAM_STR);
            $query->bindValue(":marca", $marca, PDO::PARAM_STR);
            $query->bindValue(":cor", $cor, PDO::PARAM_STR);
            $query->bindValue(":ano", $ano, PDO::PARAM_INT);
            $query->bindValue(":status_veiculo", $status_veiculo, PDO::PARAM_STR);
            $query->bindValue(":id_veiculo", $id_veiculo, PDO::PARAM_INT);

            // Executar a query
            $query->execute();

            
            //echo "Veículo alterado com sucesso!";
            return true;
        } catch (PDOException $e) {
            echo "Erro ao alterar: " . $e->getMessage();
            return false;
        }
    }
}

/*$objveiculo = new Veiculo();
$objveiculo->alterar_veiculo(
    "Uno com escada",     // modelo
    "BRA2E19",             // placa
    "Fiat",                // marca
    "Prata",               // cor
    2012,                  // ano
    "Manutenção",          // status_veiculo
    51                      // id_veiculo
);*/
