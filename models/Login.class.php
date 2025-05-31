<?php

class Login extends Conexao 
{
    private $id_usuario;   
    private $senha;
    private $user;

    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    public function setIdUsuario($id_usuario): self
    {
        $this->id_usuario = $id_usuario;

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

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user): self
    {
        $this->user = $user;

        return $this;
    }
    public function login ($user, $senha)
    {
        $this->setUser($user);
        $this->setSenha($senha);

        //sql
        $sql = "SELECT COUNT(*) AS quantidade FROM tb_usuario where user= :user and senha= :senha";

        try {
            $db = $this->conectar();
            $query = $db->prepare ($sql);
            $query->bindValue( ":user", $user, PDO::PARAM_STR);
            $query->bindValue(":senha", $senha, PDO::PARAM_STR);
            $query->execute();
            //retorna o resultado
            $resultado = $query->fetchAll(PDO::FETCH_OBJ);
            //verificar resultado
            foreach ($resultado as $key => $value) {
                $quantidade = $value->quantidade;
            }

            //testar quantidade

            if ($quantidade == 1) {
                //echo "Conectar";
                return true;
            } else {
                //echo 'ErrooooUUUUUU';
                return false;
            }
        } catch (PDOException $e) {
            //print 'Erro ao ENTAR' .$e->errorInfo;
            return false;
        }



    }
    
}


//$Login = new Login_class();
//$Login->login("ROMULLO.FRANCA","123456");