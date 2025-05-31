<?php

require_once __DIR__ . '/../models/Usuario.class.php';
require_once __DIR__ . '/../models/Motorista.class.php';
require_once __DIR__ . '/../models/veiculo.class.php';
require_once __DIR__ . '/../models/Pedido.class.php';
require_once __DIR__ . '/../models/Rotas.class.php';
require_once __DIR__ . '/../models/Rastreio.class.php';






class Controller
{

    public function redirecionar($pagina)
    {
        //iniciar sessao
        session_start();
        //incluir menu
        $menu = $this->menu();
        //incluir a view
        require_once 'views/' . $pagina . '.php';
    }

    //validar usuario
    public function login_class($user, $senha)
    {
        $objLogin_class = new Login();
        //validar usuario,
        if ($objLogin_class->login($user, $senha) == true) {

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['user'] = $user;
            //menu
            $menu = $this->menu();
            //$rodape = $this->rodape();
            include_once 'views/home.php';



            //header('location : /../views/views_home.php');

        } else {
            include_once 'login.php';
            $this->mostrarMensagem("Login ou senha inválidos!");
        }
    }
    //inserir usuario
    public function inserir_usuarios($nomeCompleto, $cpf, $user, $senha, $telefone, $id_tipo, $id_status_func, $foto, $id_usuario, $email)
    {
        //instaciar classe Inserir_usuario
        $objInserir_usuario = new Usuario();

        //invocar metodo
        if ($objInserir_usuario->inserirUsuario($nomeCompleto, $cpf, $user, $senha, $telefone, $id_tipo, $id_status_func, $foto, $id_usuario, $email) == true) {
            session_start();
            //inserir menu
            $menu = $this->menu();
            $resultado = $objInserir_usuario->exibirUsuario(null);
            //incluir a view
            include_once 'views/usuarios.php';
            //mostrar mensagem
            $this->mostrarMensagem("Usuario inserido com sucesso!");
        } else {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'views/usuarios.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao inserir Usuario!");
        }
    }

    //visualizar usuarios 
    public function mostrar_usuario($nomeCompleto)
    {
        session_start();

        $objUsuario = new Usuario();
        $resultado = $objUsuario->exibirUsuario($nomeCompleto);

        // Inserir menu
        $menu = $this->menu();

        // Verifica se veio resultado ou deu erro
        if ($resultado === false) {
            $this->mostrarMensagem("Erro ao consultar!");
        }

        include_once 'views/usuarios.php';
    }


    //excluir usuarios 
    public function excluir_usuario($id_usuario)
    {
        $objUsuario = new Usuario();

        $resultado = $objUsuario->excluir_usuario($id_usuario);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $menu = $this->menu();

        include_once 'views/usuarios.php';

        if ($resultado) {
            $this->mostrarMensagem("Usuário excluído com sucesso!");
        } else {
            $this->mostrarMensagem("Erro ao excluir usuário!");
        }
    }

    //funçao alterar usuario
    public function alterar_usuario($nomeCompleto, $senha, $telefone, $id_tipo, $id_status_func, $foto, $email, $id_usuario)
    {
        $objUsuario = new Usuario();

        if ($objUsuario->alterar_usuario($nomeCompleto, $senha, $telefone, $id_tipo, $id_status_func, $foto, $email, $id_usuario) === true) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            include_once 'views/usuarios.php';
            $this->mostrarMensagem('Usuário editado com sucesso!');
        } else {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            include_once 'views/usuarios.php';
            $this->mostrarMensagem('Falha no processo de edição. Verifique os dados.');
        }
    }

    //motorista

    public function inserir_motorista($id_usuario,  $cnh, $categoria, $validade_cnh)
    {
        $objMotorista = new Motorista();
        if (isset($_SESSION)) {
            session_start();
        }

        if ($objMotorista->inserir_motorista($id_usuario,  $cnh, $categoria, $validade_cnh) == true) {
            $menu = $this->menu();
            include_once 'views/motorista.php';
            //mostrar mensagem
            $this->mostrarMensagem("Motorista inserido com sucesso!");
        } else {
            $menu = $this->menu();
            //incluir a view
            include_once 'views/motorista.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao inserir o motorista!");
        }
    }

    //Mostrar motorista
    public function mostrar_motorista($nomeCompleto)
    {
        session_start();

        $objUsuario = new Motorista();
        $resultado = $objUsuario->exibirMotorista($nomeCompleto);

        // Inserir menu
        $menu = $this->menu();

        if ($resultado === false) {
            $this->mostrarMensagem("Erro ao consultar!");
        } else {
            include_once 'views/motorista.php';
            //print_r ($resultado);
        }
    }


    //excluir usuarios 
    public function excluir_Motorista($id_Motorista)
    {
        $objUsuario = new Motorista();

        $resultado = $objUsuario->excluir_motorista($id_Motorista);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $menu = $this->menu();

        include_once 'views/motorista.php';

        if ($resultado) {
            $this->mostrarMensagem("Motorista excluído com sucesso!");
        } else {
            $this->mostrarMensagem("Erro ao excluir Motorista!");
        }
    }


    //funçao alterar Motorista
    public function alterar_Motorista($id_Motorista, $cnh, $categoria, $validade_cnh)
    {
        $objUsuario = new Motorista();
        // Inicia a sessão se necessário

        $menu = $this->menu();



        if ($objUsuario->alterar_Motorista($id_Motorista, $categoria, $validade_cnh) === true) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            include_once 'views/motorista.php';
            $this->mostrarMensagem('Motorista editado com sucesso!');
        } else {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            include_once 'views/motorista.php';
            $this->mostrarMensagem('Falha no processo de edição. Verifique os dados.');
        }
    }

    //VEICULO

    public function inserirVeiculo($placa, $modelo, $ano, $marca, $cor, $status_veiculo, $observacoes)
    {
        $objveiculo = new Veiculo();

        if ($objveiculo->InserirVeiculo($placa, $modelo, $ano, $marca, $cor, $status_veiculo, $observacoes) === true) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            };
            $menu = $this->menu();

            include_once 'views/veiculo.php';
            $this->mostrarMensagem('Veículo inserido com sucesso');
        } else {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $menu = $this->menu();

            include_once 'views/veiculo.php';
            $this->mostrarMensagem('Falha no processo de inserir veículo');
        }
    }


    //MOSTRAR VEICULO
    public function mostrarVeiculo($placa)
    {
        $objveiculo = new Veiculo();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        };

        $resultado = $objveiculo->mostrarVeiculo($placa);

        $menu = $this->menu();

        if ($resultado === false) {
            $this->mostrarMensagem("Erro ao consultar!");
        } else {
            include_once 'views/veiculo.php';
        }
    }


    //EXCLUIR VEICULO
    public function excluir_veiculo($id_veiculo)
    {
        $objveiculo = new Veiculo();

        $resultado = $objveiculo->excluir_veiculo($id_veiculo);

        // Inicia a sessão se necessário
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $menu = $this->menu();

        include_once 'views/veiculo.php';

        if ($resultado) {
            $this->mostrarMensagem("Veiculo excluído com sucesso!");
        } else {
            $this->mostrarMensagem("Erro ao excluir Veiculo!");
        }
    }


    //funçao alterar veiculo
    public function alterar_veiculo($modelo, $marca, $cor, $ano, $status_veiculo, $id_veiculo)
    {
        $objveiculo = new Veiculo();
        // Inicia a sessão se necessário

        $menu = $this->menu();



        if ($objveiculo->alterar_veiculo($modelo, $marca, $cor, $ano, $status_veiculo, $id_veiculo) === true) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            include_once 'views/veiculo.php';
            $this->mostrarMensagem('Veículo editado com sucesso!');
        } else {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            include_once 'views/veiculo.php';
            $this->mostrarMensagem('Falha no processo de edição. Verifique os dados.');
        }
    }

    public function inserir_Pedido($arquivoNome, $destinatario_endereco, $destinatario_numero, $destinatario_cep, $remetente_numero, $remetente_endereco, $remetenteCpfCnpj, $remetenteNome, $pedidoNumero, $notaNumero, $chaveNota, $destinatarioCpfCnpj, $destinatarioNome, $remetente_cep,$status_rota)
    {
        $objPedido = new Pedido();

        $codigoRastreamento = $objPedido->InserirPedido($arquivoNome, $destinatario_endereco, $destinatario_numero, 
        $destinatario_cep, $remetente_numero, $remetente_endereco,
         $remetenteCpfCnpj, $remetenteNome, $pedidoNumero,
          $notaNumero, $chaveNota, $destinatarioCpfCnpj,
           $destinatarioNome, $remetente_cep,   $status_rota);

        

        if (isset($codigoRastreamento) ) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $menu = $this->menu();

            include_once 'views/cotacao.php';



            $this->mostrarMensagem('Pedido enviado com Sucesso! O código de rastreio do seu pedido é: ' . $codigoRastreamento);

            include_once 'views/cotacao.php';
        } else {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $menu = $this->menu();

            include_once 'views/cotacao.php';
            $this->mostrarMensagem('Erro no pedido!');

        }
    }

    public function exibir_pedidos($notaNumero)
    {
        $objPedido = new Pedido();
        $resultado = $objPedido->exibir_pedidos($notaNumero);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $menu = $this->menu();



        if ($resultado === false) {
            $this->mostrarMensagem('Usuario não encontrado!');
        } else {
            //print_r($resultado);
            include_once 'views/pedidos.php';
        }
    }


    // Controller
public function status_pedidos($chaves, $status)
{
    $pedidoModel = new Pedido();
    $pedidoModel->status_pedidos($chaves, $status); // agora passa array
}

    
    // ROTAS


    public function inserirRota($tipo_rota, $nome_rota, $origem, $destino, $previsao, $data_saida, $motorista_id, $veiculo_id, $observacoes, $status_rota, $distancia, $chaves)
    {
        $objrotas = new Rotas();

        if ($objrotas->inserir_rotas($tipo_rota, $nome_rota, $origem, $destino, 
        $previsao, $data_saida, $motorista_id, $veiculo_id,
         $observacoes, $status_rota, $distancia, $chaves
         ) === true) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            };
            $menu = $this->menu();

            include_once 'views/rotas.php';
            $this->mostrarMensagem('Rota inserido com sucesso');
        } else {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $menu = $this->menu();

            include_once 'views/rotas.php';
            $this->mostrarMensagem('Falha no processo de inserir rota');
        }
    }

    //RASTREIO

    public function Rastrear_pedido($id_pedidos)
    {
        $objRastreio = new Rastreio();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        };

        $resultado = $objRastreio->Rastrear_pedido($id_pedidos);

        $menu = $this->menu();

        if ($resultado === false) {
            $this->mostrarMensagem("Erro ao consultar!");
        } else {
            $pedido = $resultado['pedido'];
            $rota = $resultado['rota'];
            include_once 'views/restreio.php';
        }
    }

    //MOSTRAR VEICULO
    public function exibir_Rotas($numeroPedido)
    {
        $objRotas = new Rotas();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        };

        $rotas = $objRotas->exibir_Rotas($numeroPedido);

        $menu = $this->menu();

        if ($rotas === false) {
            $this->mostrarMensagem("Erro ao consultar!");
        } else {
            include_once 'views/rotasAjuste.php';
        }
    }


    public function alterRota($id_Rotas,$status_rota)
    {
        $objUsuario = new Rotas();

        $menu = $this->menu();


        if ($objUsuario->alterRota($id_Rotas,$status_rota) === true) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            include_once 'views/rotasAjuste.php';
            $this->mostrarMensagem('Rota editado com sucesso!');
        } else {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            include_once 'views/rotasAjuste.php';
            $this->mostrarMensagem('Falha no processo de edição. Verifique os dados.');

        }
    }




    
































    //mostrar menu
    public function menu()
    {
        print '<nav class="navbar navbar-expand-lg navbar-dark fixed-top shadow-sm" style="background-color: #3e84b0;">';
        print '  <div class="container-fluid">';
        print '    <a class="navbar-brand fw-bold text-white" href="index.php?home">DNA Transportes</a>';
        print '    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">';
        print '      <span class="navbar-toggler-icon"></span>';
        print '    </button>';
        print '    <div class="collapse navbar-collapse" id="navbarNav">';
        print '      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">';

        // Rastreio
        print '        <li class="nav-item">';
        print '          <a class="nav-link text-white" href="index.php?restreio"><i class="bi bi-truck"></i> Rastreio</a>';
        print '        </li>';

        // Operacional
        print '        <li class="nav-item dropdown">';
        print '          <a class="nav-link dropdown-toggle text-white" href="#" id="operacionalDropdown" role="button" data-bs-toggle="dropdown">';
        print '            <i class="bi bi-gear"></i> Operacional';
        print '          </a>';
        print '          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">';
        print '            <li><a class="dropdown-item" href="index.php?rotas">Rotas</a></li>';
        print '            <li><a class="dropdown-item" href="index.php?rotasAjuste">Ajuste de Rotas</a></li>';
        print '            <li><a class="dropdown-item" href="index.php?pedidos">Pedidos</a></li>';
        print '          </ul>';
        print '        </li>';

        // Cadastros
        print '        <li class="nav-item dropdown">';
        print '          <a class="nav-link dropdown-toggle text-white" href="#" id="cadastrosDropdown" role="button" data-bs-toggle="dropdown">';
        print '            <i class="bi bi-person-plus"></i> Cadastros';
        print '          </a>';
        print '          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">';
        print '            <li><a class="dropdown-item" href="index.php?veiculo">Veículo</a></li>';
        print '            <li><a class="dropdown-item" href="index.php?motorista">Motorista</a></li>';
        print '            <li><a class="dropdown-item" href="index.php?usuarios">Usuários</a></li>';
        print '          </ul>';
        print '        </li>';

        // cliente Aéreo
        print '        <li class="nav-item">';
        print '          <a class="nav-link text-white" href="index.php?cotacao"><i class="bi bi-box-seam"></i> Cotação</a>';
        print '        </li>';

        // Tracking Aéreo
        print '        <li class="nav-item">';
        print '          <a class="nav-link text-white" href="index.php?AWB"><i class="bi bi-airplane"></i> Tracking Aéreo</a>';
        print '        </li>';

        // Perfil do usuário
        print '        <li class="nav-item dropdown">';
        print '          <a class="nav-link dropdown-toggle btn btn-blue-dark text-light ms-2" href="#" data-bs-toggle="dropdown">';
        print '            <i class="bi bi-person-circle"></i> ' . $_SESSION['user'] . '
        </a>';
        print '          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">';
        print '            <li><a class="dropdown-item" href="../index.html">Sair</a></li>';
        print '          </ul>';
        print '        </li>';

        print '      </ul>';
        print '    </div>';
        print '  </div>';
        print '</nav>';
    }

    public function Rodape()
    {
        //print  '    footer class="text-center mt-4"';
        print  '        <p>Transportadora DNA - Todos os direitos reservados.</p>';
        //print  '    </footer>';
    }





    public function mostrarMensagem($mensagem)
    {
        //<!-- Modal -->
        print '<div class="modal fade" id="mensagem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
        print '  <div class="modal-dialog">';
        print '    <div class="modal-content">';
        print '      <div class="modal-header">';
        print '        <h5 class="modal-title" id="exampleModalLabel">Informação</h5>';
        print '        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        print '      </div>';
        print '      <div class="modal-body">';
        print '        <div class="alert alert-warning" role="alert">';
        print $mensagem;
        print '        </div>';
        print '      </div>';
        print '      <div class="modal-footer">';
        print '        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">X</button>';
        print '      </div>';
        print '    </div>';
        print '  </div>';
        print '</div>';

        //JS
        print '<script>';
        print '    document.addEventListener("DOMContentLoaded", function() {';
        print '    var myModal = new bootstrap.Modal(document.getElementById("mensagem"));';
        print '    myModal.show();});';
        print '</script>';
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






    //MODAL EXCUIR USUARIO
    public function modal_excluir_Motorista($id_Motorista, $nomeCompleto)
    {
        echo <<<HTML
    <div class="modal fade" id="modal_excluir_Motorista{$id_Motorista}" tabindex="-1" aria-labelledby="modal_excluir_Motorista" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modal_excluir_Motorista">Excluir Motorista</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja excluir o motorista <strong>{$nomeCompleto}</strong>?
                </div>
                <form method="post" action="index.php">
                    <div class="modal-footer">
                        <input type="hidden" name="id_Motorista" value="{$id_Motorista}">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="excluir_Motorista" class="btn btn-danger">Excluir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    HTML;
    }

    //MODAL ALTERAR USUARIO
    public function modal_alterar_motorista($id_motorista, $id_usuario, $cnh, $categoria, $validade_cnh)
    {
        print '<div class="modal fade" id="modal_alterar_motorista' . $id_motorista . '" tabindex="-1" aria-hidden="true">';
        print '  <div class="modal-dialog modal-xl modal-dialog-centered">';
        print '    <div class="modal-content">';
        print '      <div class="modal-header text-white" style="background-color: #3e84b0;">';
        print '        <h5 class="modal-title"><i class="bi bi-truck-front"></i> Alterar Motorista</h5>';
        print '        <button class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>';
        print '      </div>';
        print '      <div class="modal-body">';
        print '        <form action="index.php" method="post" class="row g-3">';

        // ID Motorista oculto 
        print '          <input type="hidden" name="id_motorista" value="' . $id_motorista . '">';

        // ID Usuário 
        print '          <div class="col-md-6">';
        print '            <label class="form-label"><i class="bi bi-person-badge"></i> ID do Usuário</label>';
        print '            <input type="text" class="form-control" value="' . $id_usuario . '" disabled>';
        print '            <input type="hidden" name="id_usuario" value="' . $id_usuario . '">';
        print '          </div>';

        // CNH
        print '          <div class="col-md-6">';
        print '            <label for="cnh_' . $id_motorista . '" class="form-label"><i class="bi bi-card-heading"></i> CNH</label>';
        print '            <input type="text" name="cnh" id="cnh_' . $id_motorista . '" class="form-control" value="' . $cnh . '" required disabled>';
        print '          </div>';

        // Categoria
        print '          <div class="col-md-6">';
        print '            <label for="categoria_' . $id_motorista . '" class="form-label"><i class="bi bi-tags"></i> Categoria</label>';
        print '            <select name="categoria" id="categoria_' . $id_motorista . '" class="form-select" required>';
        $categorias = ['A', 'B', 'C', 'D', 'E'];
        foreach ($categorias as $cat) {
            $selected = $cat === $categoria ? 'selected' : '';
            print '<option value="' . $cat . '" ' . $selected . '>' . $cat . '</option>';
        }
        print '            </select>';
        print '          </div>';

        // Validade CNH
        print '          <div class="col-md-6">';
        print '            <label for="validade_' . $id_motorista . '" class="form-label"><i class="bi bi-calendar-event"></i> Validade CNH</label>';
        print '            <input type="date" name="validade_cnh" id="validade_' . $id_motorista . '" class="form-control" value="' . $validade_cnh . '" required>';
        print '          </div>';

        // Botões
        print '          <div class="col-12 d-flex justify-content-end mt-3">';
        print '            <button type="submit" name="alterar_motorista" class="btn text-white me-2" style="background-color: #3e84b0;">';
        print '              <i class="bi bi-check-circle"></i> Salvar Alterações';
        print '            </button>';
        print '            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">';
        print '              <i class="bi bi-x-circle"></i> Cancelar';
        print '            </button>';
        print '          </div>';

        print '        </form>';
        print '      </div>';
        print '    </div>';
        print '  </div>';
        print '</div>';
    }

    // Modal Excluir Veículo
    public function modal_excluir_veiculo($id_veiculo, $modelo, $placa)
    {
        echo <<<HTML
        <div class="modal fade" id="excluir_veiculo{$id_veiculo}" tabindex="-1" aria-labelledby="excluirVeiculoLabel{$id_veiculo}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-white" style="background-color: #3e84b0;">
                    <h5 class="modal-title" id="excluirVeiculoLabel{$id_veiculo}">
                        <i class="bi bi-trash"></i> Excluir Veículo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <p>Você tem certeza que deseja excluir o veículo <strong>{$modelo} - {$placa}</strong>?</p>
                    <p class="text-danger">Esta ação não pode ser desfeita.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="index.php" method="post">
                        <input type="hidden" name="id_veiculo" value="{$id_veiculo}">
                        <button type="submit" name="excluirVeiculo" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Excluir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
HTML;
    }


    // Modal Editar Veículo
    // MODAL ALTERAR VEÍCULO
    public function modal_alterar_veiculo($id_veiculo, $modelo, $placa, $marca, $cor, $ano, $status_veiculo)
    {
        print '<div class="modal fade" id="modal_alterar_veiculo' . $id_veiculo . '" tabindex="-1" aria-hidden="true">';
        print '  <div class="modal-dialog modal-xl modal-dialog-centered">';
        print '    <div class="modal-content">';
        print '      <div class="modal-header text-white" style="background-color: #3e84b0;">';
        print '        <h5 class="modal-title"><i class="bi bi-truck"></i> Alterar Veículo</h5>';
        print '        <button class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>';
        print '      </div>';
        print '      <div class="modal-body">';
        print '        <form action="index.php" method="post" class="row g-3">';

        // ID oculto
        print '          <input type="hidden" name="id_veiculo" value="' . $id_veiculo . '">';

        // Modelo
        print '          <div class="col-md-6">';
        print '            <label for="modelo_' . $id_veiculo . '" class="form-label"><i class="bi bi-car-front-fill"></i> Modelo</label>';
        print '            <input type="text" name="modelo" id="modelo_' . $id_veiculo . '" class="form-control" value="' . $modelo . '"  required> ';
        print '          </div>';

        // Placa
        print '          <div class="col-md-6">';
        print '            <label for="placa_' . $id_veiculo . '" class="form-label"><i class="bi bi-input-cursor-text"></i> Placa</label>';
        print '            <input type="text" name="placa" id="placa_' . $id_veiculo . '" class="form-control" value="' . $placa . '" disabled required>';
        print '          </div>';

        // Marca
        print '          <div class="col-md-6">';
        print '            <label for="marca_' . $id_veiculo . '" class="form-label"><i class="bi bi-building"></i> Marca</label>';
        print '            <input type="text" name="marca" id="marca_' . $id_veiculo . '" class="form-control" value="' . $marca . '"  required> ';
        print '          </div>';

        // Cor
        print '          <div class="col-md-6">';
        print '            <label for="cor_' . $id_veiculo . '" class="form-label"><i class="bi bi-palette"></i> Cor</label>';
        print '            <input type="text" name="cor" id="cor_' . $id_veiculo . '" class="form-control" value="' . $cor . '" required>';
        print '          </div>';

        // Ano
        print '          <div class="col-md-6">';
        print '            <label for="ano_' . $id_veiculo . '" class="form-label"><i class="bi bi-calendar"></i> Ano</label>';
        print '            <input type="number" name="ano" id="ano_' . $id_veiculo . '" class="form-control" value="' . $ano . '"  required>';
        print '          </div>';

        // Status
        print '          <div class="col-md-6">';
        print '            <label for="status_' . $id_veiculo . '" class="form-label"><i class="bi bi-toggles"></i> Status</label>';
        print '            <select name="status_veiculo" id="status_' . $id_veiculo . '" class="form-select" required>';
        $statusOptions = ['Ativo', 'Manutenção', 'Inativo'];
        foreach ($statusOptions as $status) {
            $selected = $status === $status_veiculo ? 'selected' : '';
            print '<option value="' . $status . '" ' . $selected . '>' . $status . '</option>';
        }
        print '            </select>';
        print '          </div>';

        // Botões
        print '          <div class="col-12 d-flex justify-content-end mt-3">';
        print '            <button type="submit" name="alterar_veiculo" class="btn text-white me-2" style="background-color: #3e84b0;">';
        print '              <i class="bi bi-check-circle"></i> Salvar Alterações';
        print '            </button>';
        print '            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">';
        print '              <i class="bi bi-x-circle"></i> Cancelar';
        print '            </button>';
        print '          </div>';

        print '        </form>';
        print '      </div>';
        print '    </div>';
        print '  </div>';
        print '</div>';
    }

    public function modal_visualizar_pedidos(
        $id_pedido,
        $remetente_cpf_cnpj,
        $remetente_nome,
        $remetente_cep,
        $remetente_endereco,
        $remetente_numero,
        $pedido_numero,
        $nota_numero,
        $chave_nota,
        $destinatario_cpf_cnpj,
        $destinatario_nome,
        $destinatario_cep,
        $destinatario_endereco,
        $destinatario_numero,
        $arquivo_nome
    ) {
        print '<div class="modal fade" id="modal_visualizar_pedido' . $id_pedido . '" tabindex="-1" aria-hidden="true">';
        print '  <div class="modal-dialog modal-xl modal-dialog-centered">';
        print '    <div class="modal-content">';
        print '      <div class="modal-header text-white" style="background-color: #3e84b0;">';
        print '        <h5 class="modal-title"><i class="bi bi-receipt-cutoff"></i> Detalhes do Pedido</h5>';
        print '        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>';
        print '      </div>';
        print '      <div class="modal-body">';
        print '        <div class="row g-3">';
    
        // Pedido e Nota
        print '          <div class="col-md-4">';
        print '            <label class="form-label"><i class="bi bi-hash"></i> Nº do Pedido</label>';
        print '            <input type="text" class="form-control" value="' . $pedido_numero . '" readonly>';
        print '          </div>';
    
        print '          <div class="col-md-4">';
        print '            <label class="form-label"><i class="bi bi-123"></i> Nº da Nota</label>';
        print '            <input type="text" class="form-control" value="' . $nota_numero . '" readonly>';
        print '          </div>';
    
        print '          <div class="col-md-4">';
        print '            <label class="form-label"><i class="bi bi-key"></i> Chave da Nota</label>';
        print '            <input type="text" class="form-control" value="' . $chave_nota . '" readonly>';
        print '          </div>';
    
        // Remetente
        print '          <div class="col-12"><hr><strong><i class="bi bi-send"></i> Remetente</strong></div>';
    
        print '          <div class="col-md-6">';
        print '            <label class="form-label">Nome</label>';
        print '            <input type="text" class="form-control" value="' . $remetente_nome . '" readonly>';
        print '          </div>';
    
        print '          <div class="col-md-6">';
        print '            <label class="form-label">CPF/CNPJ</label>';
        print '            <input type="text" class="form-control" value="' . $remetente_cpf_cnpj . '" readonly>';
        print '          </div>';
    
        print '          <div class="col-md-4">';
        print '            <label class="form-label">CEP</label>';
        print '            <input type="text" class="form-control" value="' . $remetente_cep . '" readonly>';
        print '          </div>';
    
        print '          <div class="col-md-6">';
        print '            <label class="form-label">Endereço</label>';
        print '            <input type="text" class="form-control" value="' . $remetente_endereco . '" readonly>';
        print '          </div>';
    
        print '          <div class="col-md-2">';
        print '            <label class="form-label">Número</label>';
        print '            <input type="text" class="form-control" value="' . $remetente_numero . '" readonly>';
        print '          </div>';
    
        // Destinatário
        print '          <div class="col-12"><hr><strong><i class="bi bi-box-arrow-in-down"></i> Destinatário</strong></div>';
    
        print '          <div class="col-md-6">';
        print '            <label class="form-label">Nome</label>';
        print '            <input type="text" class="form-control" value="' . $destinatario_nome . '" readonly>';
        print '          </div>';
    
        print '          <div class="col-md-6">';
        print '            <label class="form-label">CPF/CNPJ</label>';
        print '            <input type="text" class="form-control" value="' . $destinatario_cpf_cnpj . '" readonly>';
        print '          </div>';
    
        print '          <div class="col-md-4">';
        print '            <label class="form-label">CEP</label>';
        print '            <input type="text" class="form-control" value="' . $destinatario_cep . '" readonly>';
        print '          </div>';
    
        print '          <div class="col-md-6">';
        print '            <label class="form-label">Endereço</label>';
        print '            <input type="text" class="form-control" value="' . $destinatario_endereco . '" readonly>';
        print '          </div>';
    
        print '          <div class="col-md-2">';
        print '            <label class="form-label">Número</label>';
        print '            <input type="text" class="form-control" value="' . $destinatario_numero . '" readonly>';
        print '          </div>';
    
        // Arquivo (se houver)
        if (!empty($arquivo_nome)) {
            print '          <div class="col-12"><hr><strong><i class="bi bi-paperclip"></i> Arquivo Anexado</strong></div>';
            print '          <div class="col-12">';
            print '            <a href="uploads/' . $arquivo_nome . '" target="_blank" class="btn btn-outline-secondary">';
            print '              <i class="bi bi-file-earmark-text"></i> Visualizar Arquivo';
            print '            </a>';
            print '          </div>';
        }
    
        print '        </div>';
        print '      </div>';
        print '      <div class="modal-footer">';
        print '        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">';
        print '          <i class="bi bi-x-circle"></i> Fechar';
        print '        </button>';
        print '      </div>';
        print '    </div>';
        print '  </div>';
        print '</div>';
    }
    
}


    /*$controller = new Controller();
    $controller->inserirRota("Rota de Teste",               // Tipo da Rota
    "Rota Teste 001",              // Nome da Rota
    "Origem Teste",                // Origem
    "Destino Teste",               // Destino
    "2025-04-15 14:00:00",         // Previsão de entrega
    "2025-04-15 08:00:00",         // Data de saída
    7,                             // Motorista ID
    52,                             // Veículo ID
    "Observação de teste",         // Observações
    "Ativa",                       // Status da Rota
    150,                           // Distância
    ["444444444444445"]);*/
