<?php
require_once __DIR__ . "/Conexao.class.php";


class Pedido extends Conexao
{
    //remetente
    private $remetenteCpfCnpj;
    private $remetenteNome;
    private $remetente_cep;
    private $remetente_endereco;
    private $remetente_numero;

    //pedido
    private $pedidoNumero;
    private $notaNumero;
    private $chaveNota;

    //destinatario
    private $destinatarioCpfCnpj;
    private $destinatarioNome;

    private $destinatario_cep;
    private $destinatario_numero;
    private $destinatario_endereco;

    //adicionais    
    private $data;
    private $id_pedidos;
    private $arquivoNome;

    private $status_pedido;


    public function getstatus_pedido()
    {
        return $this->status_pedido;
    }

    public function setstatus_pedido($status_pedido): self
    {
        $this->status_pedido = $status_pedido;

        return $this;
    }

    public function getArquivoNome()
    {
        return $this->arquivoNome;
    }

    public function setArquivoNome($arquivoNome): self
    {
        $this->arquivoNome = $arquivoNome;

        return $this;
    }



    public function getRemetenteCpfCnpj()
    {
        return $this->remetenteCpfCnpj;
    }

    public function setRemetenteCpfCnpj($remetenteCpfCnpj): self
    {
        $this->remetenteCpfCnpj = $remetenteCpfCnpj;

        return $this;
    }

    public function getRemetenteNome()
    {
        return $this->remetenteNome;
    }

    public function setRemetenteNome($remetenteNome): self
    {
        $this->remetenteNome = $remetenteNome;

        return $this;
    }

    public function getRemetenteCep()
    {
        return $this->remetente_cep;
    }

    public function setRemetenteCep($remetente_cep): self
    {
        $this->remetente_cep = $remetente_cep;

        return $this;
    }

    public function getRemetenteEndereco()
    {
        return $this->remetente_endereco;
    }

    public function setRemetenteEndereco($remetente_endereco): self
    {
        $this->remetente_endereco = $remetente_endereco;

        return $this;
    }

    public function getRemetenteNumero()
    {
        return $this->remetente_numero;
    }

    public function setRemetenteNumero($remetente_numero): self
    {
        $this->remetente_numero = $remetente_numero;

        return $this;
    }

    public function getPedidoNumero()
    {
        return $this->pedidoNumero;
    }

    public function setPedidoNumero($pedidoNumero): self
    {
        $this->pedidoNumero = $pedidoNumero;

        return $this;
    }

    public function getNotaNumero()
    {
        return $this->notaNumero;
    }

    public function setNotaNumero($notaNumero): self
    {
        $this->notaNumero = $notaNumero;

        return $this;
    }

    public function getChaveNota()
    {
        return $this->chaveNota;
    }

    public function setChaveNota($chaveNota): self
    {
        $this->chaveNota = $chaveNota;

        return $this;
    }

    public function getDestinatarioCpfCnpj()
    {
        return $this->destinatarioCpfCnpj;
    }

    public function setDestinatarioCpfCnpj($destinatarioCpfCnpj): self
    {
        $this->destinatarioCpfCnpj = $destinatarioCpfCnpj;

        return $this;
    }

    public function getDestinatarioNome()
    {
        return $this->destinatarioNome;
    }

    public function setDestinatarioNome($destinatarioNome): self
    {
        $this->destinatarioNome = $destinatarioNome;

        return $this;
    }

    public function getDestinatarioCep()
    {
        return $this->destinatario_cep;
    }

    public function setDestinatarioCep($destinatario_cep): self
    {
        $this->destinatario_cep = $destinatario_cep;

        return $this;
    }

    public function getDestinatarioNumero()
    {
        return $this->destinatario_numero;
    }

    public function setDestinatarioNumero($destinatario_numero): self
    {
        $this->destinatario_numero = $destinatario_numero;

        return $this;
    }

    public function getDestinatarioEndereco()
    {
        return $this->destinatario_endereco;
    }

    public function setDestinatarioEndereco($destinatario_endereco): self
    {
        $this->destinatario_endereco = $destinatario_endereco;

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getIdPedidos()
    {
        return $this->id_pedidos;
    }

    public function setIdPedidos($id_pedidos): self
    {
        $this->id_pedidos = $id_pedidos;

        return $this;
    }


    public function InserirPedido($arquivoNome, $destinatario_endereco, $destinatario_numero, $destinatario_cep, $remetente_numero, $remetente_endereco, $remetenteCpfCnpj, $remetenteNome, $pedidoNumero, $notaNumero, $chaveNota, $destinatarioCpfCnpj, $destinatarioNome, $remetente_cep, $status_pedido)
    {
        //remetente
        $this->setRemetenteCpfCnpj($remetenteCpfCnpj);
        $this->setRemetenteNome($remetenteNome);
        $this->setRemetenteCep($remetente_cep);
        $this->setRemetenteEndereco($remetente_endereco);
        $this->setRemetenteNumero($remetente_numero);

        // Informações do Pedido
        $this->setPedidoNumero($pedidoNumero);
        $this->setNotaNumero($notaNumero);
        $this->setChaveNota($chaveNota);

        //Destinatário
        $this->setDestinatarioCpfCnpj($destinatarioCpfCnpj);
        $this->setDestinatarioNome($destinatarioNome);

        $this->setDestinatarioCep($destinatario_cep);

        $this->setDestinatarioNumero($destinatario_numero);

        $this->setArquivoNome($arquivoNome);
        $this->setstatus_pedido($status_pedido);



        $codigoRastreamento = $this->gerarCodigoRastreio();

        $sql = "INSERT INTO tb_pedidos 
                (remetente_cpf_cnpj, remetente_nome, remetente_cep, remetente_endereco, remetente_numero,
                pedido_numero, nota_numero, chave_nota,
                destinatario_cpf_cnpj, destinatario_nome, destinatario_cep, destinatario_endereco, destinatario_numero,
                arquivo_nome, codigo_rastreamento,status_pedido)
                VALUES 
                (:remetente_cpf_cnpj, :remetente_nome, :remetente_cep, :remetente_endereco, :remetente_numero,
                :pedido_numero, :nota_numero, :chave_nota,
                :destinatario_cpf_cnpj, :destinatario_nome, :destinatario_cep, :destinatario_endereco, :destinatario_numero,
                :arquivo_nome, :codigo_rastreamento,:status_pedido)";

        try {
            $db = $this->conectar();
            $query = $db->prepare($sql);

            $query->bindValue(":remetente_cpf_cnpj", $remetenteCpfCnpj, PDO::PARAM_STR);
            $query->bindValue(":remetente_nome", $remetenteNome, PDO::PARAM_STR);
            $query->bindValue(":remetente_cep", $remetente_cep, PDO::PARAM_STR);
            $query->bindValue(":remetente_endereco", $remetente_endereco, PDO::PARAM_STR);
            $query->bindValue(":remetente_numero", $remetente_numero, PDO::PARAM_INT);

            $query->bindValue(":pedido_numero", $pedidoNumero, PDO::PARAM_STR);
            $query->bindValue(":nota_numero", $notaNumero, PDO::PARAM_STR);
            $query->bindValue(":chave_nota", $chaveNota, PDO::PARAM_STR);

            $query->bindValue(":destinatario_cpf_cnpj", $destinatarioCpfCnpj, PDO::PARAM_STR);
            $query->bindValue(":destinatario_nome", $destinatarioNome, PDO::PARAM_STR);
            $query->bindValue(":destinatario_cep", $destinatario_cep, PDO::PARAM_STR);
            $query->bindValue(":destinatario_endereco", $destinatario_endereco, PDO::PARAM_STR);
            $query->bindValue(":destinatario_numero", $destinatario_numero, PDO::PARAM_INT);

            $query->bindValue(":arquivo_nome", $arquivoNome, PDO::PARAM_STR);
            $query->bindValue(":codigo_rastreamento", $codigoRastreamento);
            $query->bindValue(":status_pedido", $status_pedido);



            $query->execute();

            return $codigoRastreamento;

            //return true;
        } catch (Exception $e) {
            echo "Erro" . $e->getMessage();
        }
    }

    //mostrar
    public function exibir_pedidos($notaNumero = null)
    {
        $sql = "SELECT * FROM tb_pedidos WHERE 1=1";

        if (!empty($notaNumero)) {
            $sql .= " AND nota_numero LIKE :notaNumero";
        }

        $sql .= " ORDER BY nota_numero";

        try {
            $bd = $this->conectar();
            $query = $bd->prepare($sql);

            if (!empty($notaNumero)) {
                $nomeBusca = "%" . $notaNumero . "%";
                $query->bindValue(':notaNumero', $nomeBusca, PDO::PARAM_STR);
            }

            $query->execute();
            return $query->fetchAll(PDO::FETCH_OBJ);



            //print_r($resul);
        } catch (PDOException $e) {
            echo 'Erro' . $e->getMessage();
            return false;
        }
    }


    //codigo de rastrio
    public function gerarCodigoRastreio()
    {
        $data = date('Ymd');
        $sql = "SELECT COUNT(*) as total FROM tb_pedidos WHERE DATE(data) = CURDATE()";
        $db = $this->conectar();
        $stmt = $db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $numero = $result['total'] + 1;
        return "177-$data" . str_pad($numero, 3, '0', STR_PAD_LEFT);
    }


    public function status_pedidos(array $chavesNota, string $status_pedidos)
    {
        $this->setstatus_pedido($status_pedidos);
        $db = $this->conectar();

        foreach ($chavesNota as $chaveNota) {
            $this->setChaveNota($chaveNota);

            $sql = "SELECT id_pedidos FROM tb_pedidos WHERE chave_nota = :chaveNota";
            $query = $db->prepare($sql);
            $query->bindValue(':chaveNota', $chaveNota, PDO::PARAM_STR);
            $query->execute();

            $pedido = $query->fetch(PDO::FETCH_ASSOC);

            if ($pedido) {
                $id_pedidos = $pedido['id_pedidos'];

                $sqlUpdate = "UPDATE tb_pedidos 
                              SET status_pedido = :status_pedido 
                              WHERE id_pedidos = :id_pedidos";

                try {
                    $queryUpdate = $db->prepare($sqlUpdate);
                    $queryUpdate->bindValue(':status_pedido', $status_pedidos, PDO::PARAM_STR);
                    $queryUpdate->bindValue(':id_pedidos', $id_pedidos, PDO::PARAM_INT);
                    $queryUpdate->execute();

                    echo "✔️ Status do pedido com chave $chaveNota atualizado com sucesso!<br>";
                } catch (Exception $e) {
                    echo "❌ Erro ao atualizar chave $chaveNota: " . $e->getMessage() . "<br>";
                }
            } else {
                echo "⚠️ Chave $chaveNota não encontrada na tabela!<br>";
            }
        }
    }
}
/*$obj = new Pedido();
$obj = new Pedido();
$obj->status_pedidos(
    [
        '22222222222222222222222222222222222222222222',
        '52230902391701001104550050000055511358206815',
        '35250412420164000580550010001560031353237622'
    ],
    'Em rota de entrega'
);*/

