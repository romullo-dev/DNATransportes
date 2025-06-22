<?php
class ControllerUsuario
{
    private $mensagem;
    private $tipo;
    private $Usuario;
    public function __construct()
    {
        $this->Usuario = new Usuario();
    }

    public function login($dados)
    {
        if ($this->Usuario->Login($dados) == true) {

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user'] = $dados['user'];
            include 'views/Home.php';
        } else {
            include 'login.php';
            $this->mostrarMensagem('Login ou senha incorreta!', 'danger');
        }

        header("Location: index.php?home");
        exit;
    }

    public function read($dados)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        };

        $resultado = $this->Usuario->readLike($dados, 'nome');

        if ($resultado === false) {
            $this->mostrarMensagem('Login ou senha incorreta!', 'danger');
        }
        include_once 'views/Usuarios.php';
    }

    public function inserir($dados)
    {
        $this->Usuario->inserir($dados);

        include_once 'views/Usuarios.php';
    }

    public function excluir_usuario($dados)
    {
        $objUsuario = new Usuario();
        $resultado = $objUsuario->delete($dados);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }


        include_once 'views/Usuarios.php';

        $resultado = $resultado ? $this->mostrarMensagem("Usuário excluído com sucesso!") : $this->mostrarMensagem("Erro ao excluir usuário!");
    }

    public function mostrarMensagem($mensagem, $tipo = 'warning')
    {
        $tiposValidos = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'];
        if (!in_array($tipo, $tiposValidos)) {
            $tipo = 'warning';
        }
        $this->mensagem = $mensagem;
        $this->tipo = $tipo;
        include 'templates/alert.php';
    }




    //MODAL EXCUIR USUARIO
    public function modal_excluir_usuario($id_usuario, $nomeCompleto)
    {
        echo <<<HTML
    <div class="modal fade" id="excluir_usuario{$id_usuario}" tabindex="-1" aria-labelledby="modalExcluirUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalExcluirUsuarioLabel">Excluir Usuário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja excluir o usuário <strong>{$nomeCompleto}</strong>?
                </div>
                <form method="post" action="index.php">
                    <div class="modal-footer">
                        <input type="hidden" name="id_usuario" value="{$id_usuario}">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="excluir_usuario" class="btn btn-danger">Excluir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    HTML;
    }

    //MODAL ALTERAR USUARIO
    public function modal_alterar_usuario($id_usuario, $nomeCompleto, $cpf, $user, $senha, $telefone, $email, $id_tipo, $id_status_func)
    {
        print '<!-- Modal -->';
        print '<div class="modal fade" id="modal_alterar_usuario' . $id_usuario . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
        print ' <div class="modal-dialog modal-lg modal-dialog-centered">';
        print '     <div class="modal-content">';
        print '      <div class="modal-header" style="background-color: #3e84b0;">';
        print '         <h5 class="modal-title" id="exampleModalLabel"><i class="bi bi-person-fill-gear"></i> Alterar Usuário</h5>';
        print '         <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>';
        print '      </div>';
        print '<form method="post" action="index.php" enctype="multipart/form-data">';
        print '  <div class="modal-body">';
        print '     <div class="row">';

        // Nome
        print '     <div class="col-md-6 mb-3">';
        print '         <label for="nome" class="form-label">Nome Completo</label>';
        print '         <input type="text" name="nomeCompleto" class="form-control" value="' . $nomeCompleto . '" required>';
        print '     </div>';

        // CPF
        print '     <div class="col-md-6 mb-3">';
        print '         <label for="cpf" class="form-label">CPF</label>';
        print ' <input type="text" name="cpf" class="form-control" value="' . $cpf . '" required disabled>';
        print '     </div>';

        // Usuário
        print '     <div class="col-md-6 mb-3">';
        print '         <label for="user" class="form-label">Usuário</label>';
        print '         <input type="text" name="user" class="form-control" value="' . $user . '" required disabled>';
        print '     </div>';

        // Senha
        print '     <div class="col-md-6 mb-3">';
        print '         <label for="senha" class="form-label">Senha</label>';
        print '         <input type="text" name="senha" class="form-control" value="' . $senha . '" required>';
        print '     </div>';

        // Telefone
        print '     <div class="col-md-6 mb-3">';
        print '         <label for="telefone" class="form-label">Telefone</label>';
        print '         <input type="text" name="telefone" class="form-control" value="' . $telefone . '" required>';
        print '     </div>';

        // Email
        print '     <div class="col-md-6 mb-3">';
        print '         <label for="email" class="form-label">Email</label>';
        print '         <input type="email" name="email" class="form-control" value="' . $email . '" required>';
        print '     </div>';

        // Tipo
        print '     <div class="col-md-6 mb-3">';
        print '         <label for="id_tipo" class="form-label">Tipo</label>';
        print '         <select name="id_tipo" class="form-select" required>';
        print '             <option value="Motorista" ' . ($id_tipo == 'Motorista' ? 'selected' : '') . '>Motorista</option>';
        print '             <option value="ADM" ' . ($id_tipo == 'ADM' ? 'selected' : '') . '>ADM</option>';
        print '             <option value="Usuario" ' . ($id_tipo == 'Usuario' ? 'selected' : '') . '>Usuário</option>';
        print '             <option value="Cliente" ' . ($id_tipo == 'Cliente' ? 'selected' : '') . '>Cliente</option>';
        print '         </select>';
        print '     </div>';

        // Status do Funcionário
        print '     <div class="col-md-6 mb-3">';
        print '         <label for="id_status_func" class="form-label">Status</label>';
        print '         <select name="id_status_func" class="form-select" required>';
        print '             <option value="Ativo" ' . ($id_status_func == 'Ativo' ? 'selected' : '') . '>Ativo</option>';
        print '             <option value="Inativo" ' . ($id_status_func == 'Inativo' ? 'selected' : '') . '>Inativo</option>';
        print '         </select>';
        print '     </div>';

        print '<input type="hidden" name="id_usuario" value="' . $id_usuario . '">';

        print '     <div class="col-md-6 mb-3">';
        print '         <label for="foto" class="form-label">Foto do Funcionário (se desejar alterar)</label>';
        print '         <input type="file" name="foto" class="form-control" accept="image/*">';
        print '     </div>';

        print '  </div>';
        print '  </div>';
        print '  <div class="modal-footer">';
        print '    <input type="hidden" name="id_autor" value="' . $id_usuario . '">';
        print '    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>';
        print '    <button type="submit" name="alterar_usuario" class="btn btn-primary">Alterar</button>';
        print '  </div>';
        print '</form>';
        print '</div>';
        print '</div>';
        print '</div>';
    }
}
