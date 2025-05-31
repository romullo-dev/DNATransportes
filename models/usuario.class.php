<?php

require_once __DIR__ . "/Conexao.class.php";


class Usuario extends Conexao
{
        private $nomeCompleto;
        private $cpf;
        private $user;
        private $senha;
        private $telefone;
        private $id_tipo;
        private $id_status_func;
        private $foto;
        private $id_usuario;
        private $email;





        //get e set 

        public function getEmail()
        {
                return $this->email;
        }

        public function setEmail($email): self
        {
                $this->email = $email;

                return $this;
        }
        public function getNomeCompleto()
        {
                return $this->nomeCompleto;
        }

        public function setNomeCompleto($nomeCompleto): self
        {
                $this->nomeCompleto = $nomeCompleto;

                return $this;
        }

        public function getCpf()
        {
                return $this->cpf;
        }

        public function setCpf($cpf): self
        {
                $this->cpf = $cpf;

                return $this;
        }

        public function getUser()
        {
                return $this->user;
        }

        public function setUser($user): self
        {
                $this->user = $user;

                return $this;
        }

        public function getSenha()
        {
                return $this->senha;
        }

        public function setSenha($senha): self
        {
                $this->senha = $senha;

                return $this;
        }

        public function getTelefone()
        {
                return $this->telefone;
        }

        public function setTelefone($telefone): self
        {
                $this->telefone = $telefone;

                return $this;
        }

        public function getIdTipo()
        {
                return $this->id_tipo;
        }

        public function setIdTipo($id_tipo): self
        {
                $this->id_tipo = $id_tipo;

                return $this;
        }

        public function getIdStatusFunc()
        {
                return $this->id_status_func;
        }

        public function setIdStatusFunc($id_status_func): self
        {
                $this->id_status_func = $id_status_func;

                return $this;
        }

        public function getFoto()
        {
                return $this->foto;
        }

        public function setFoto($foto): self
        {
                $this->foto = $foto;

                return $this;
        }

        public function getIdUsuario()
        {
                return $this->id_usuario;
        }


        public function setIdUsuario($id_usuario): self
        {
                $this->id_usuario = $id_usuario;

                return $this;
        }


        public function inserirUsuario($nomeCompleto, $cpf, $user, $senha, $telefone, $id_tipo, $id_status_func, $foto, $id_usuario, $email)
        {

                $this->setNomeCompleto($nomeCompleto);
                $this->setCpf($cpf);
                $this->setUser($user);
                $this->setSenha($senha);
                $this->setTelefone($telefone);
                $this->setIdTipo($id_tipo);
                $this->setIdStatusFunc($id_status_func);
                $this->setFoto($foto);
                $this->setIdUsuario($id_usuario);
                $this->setEmail($email);

                //MONTAR QUERY
                $sql = "INSERT INTO tb_usuario 
            (id_usuario, nomeCompleto, cpf, user, senha, telefone, id_tipo, id_status_func, foto, email) 
        VALUES 
            (NULL, :nomeCompleto, :cpf, :user, :senha, :telefone, :id_tipo, :id_status_func, :foto, :email)";

                try {
                        // Conectar ao banco
                        $db = $this->conectar();
                        if (!$db) {
                                throw new Exception("Falha na conexão com o banco de dados.");
                        }

                        // Preparar SQL
                        $query = $db->prepare($sql);

                        // Blindagem dos dados
                        $query->bindValue(":nomeCompleto",     $nomeCompleto,     PDO::PARAM_STR);
                        $query->bindValue(":cpf",              $cpf,              PDO::PARAM_STR);
                        $query->bindValue(":user",             $user,             PDO::PARAM_STR);
                        $query->bindValue(":senha",            $senha,            PDO::PARAM_STR);
                        $query->bindValue(":telefone",         $telefone,         PDO::PARAM_STR);
                        $query->bindValue(":id_tipo",          $id_tipo,          PDO::PARAM_STR);
                        $query->bindValue(":id_status_func",   $id_status_func,   PDO::PARAM_STR);
                        $query->bindValue(":foto",             $foto,             PDO::PARAM_STR);
                        $query->bindValue(":email",            $email,            PDO::PARAM_STR);

                        // Executar a query
                        $query->execute();

                        return true;
                } catch (PDOException $e) {
                        echo 'Erro Inserir: ' . $e->getMessage();
                        return false;
                }
        }

        //consultar usuario
        public function exibirUsuario($nomeCompleto = null)
        {
                $sql = "SELECT * FROM tb_usuario WHERE 1=1";

                if (!empty($nomeCompleto)) {
                        $sql .= " AND nomeCompleto LIKE :nomeCompleto";
                }

                $sql .= " ORDER BY nomeCompleto";

                try {
                        $bd = $this->conectar();
                        $query = $bd->prepare($sql);

                        if (!empty($nomeCompleto)) {
                                $nomeBusca = "%" . $nomeCompleto . "%";
                                $query->bindValue(':nomeCompleto', $nomeBusca, PDO::PARAM_STR);
                        }

                        $query->execute();
                        return $query->fetchAll(PDO::FETCH_OBJ);
                } catch (PDOException $e) {
                        echo 'Erro' . $e->getMessage();
                        return false;
                }
        }


        //método excluir Usuario
        public function excluir_usuario($id_usuario)
        {
                $this->setIdUsuario($id_usuario);

                $sql = "DELETE FROM tb_usuario WHERE id_usuario = :id_usuario";

                try {
                        $db = $this->conectar();
                        $query = $db->prepare($sql);
                        $query->bindValue(':id_usuario', $this->getIdUsuario(), PDO::PARAM_INT);
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

        //método alterar Usuario
        public function alterar_usuario($nomeCompleto, $senha, $telefone, $id_tipo, $id_status_func, $foto, $email, $id_usuario)
        {
                // Setando os valores
                $this->setIdTipo($id_tipo);
                $this->setNomeCompleto($nomeCompleto);
                $this->setSenha($senha);
                $this->setTelefone($telefone);
                $this->setIdStatusFunc($id_status_func);
                $this->setFoto($foto);
                $this->setEmail($email);
                $this->setIdUsuario($id_usuario);


                // SQL para atualizar usuário
                $sql = "UPDATE tb_usuario SET 
                        nomeCompleto = :nomeCompleto,
                        senha = :senha,
                        telefone = :telefone,
                        id_tipo = :idTipo,
                        id_status_func = :idStatusFunc,
                        email = :email";

                if (!empty($foto)) {
                        $sql .= ", foto = :foto";
                }

                $sql .= " WHERE id_usuario = :id_usuario";
                try {
                        // Conectar ao banco
                        $db = $this->conectar();
                        // Preparar o SQL
                        $query = $db->prepare($sql);

                        // blindar valores
                        $query->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
                        $query->bindValue(":nomeCompleto", $nomeCompleto, PDO::PARAM_STR);
                        $query->bindValue(":senha", $senha, PDO::PARAM_STR);
                        $query->bindValue(":telefone", $telefone, PDO::PARAM_STR);
                        $query->bindValue(":idTipo", $id_tipo, PDO::PARAM_STR);
                        $query->bindValue(":idStatusFunc", $id_status_func, PDO::PARAM_STR);
                        $query->bindValue(":email", $email, PDO::PARAM_STR);

                        if (!empty($foto)) {
                                $query->bindValue(":foto", $foto);
                        }

                        // Executar a query
                        $query->execute();

                        //echo "Usuário alterado com sucesso!";
                        return true;
                } catch (PDOException $e) {
                        echo "Erro ao alterar: " . $e->getMessage();
                        return false;
                }
        }
}




/*$usuario = new Usuario();
$usuario->alterar_usuario(
        "Maria Oliveira",
        "senha123",
        "11912345678",
        'Motorista',
        "Ativo",
        "foto_maria.jpg",
        "maria.oliveira@teste.com",
        6
);*/
