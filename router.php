<?php

require_once __DIR__ . "/autoload.php";

//pegar a url
$url = explode('?', $_SERVER['REQUEST_URI']);
//escolher na variável $url do link desejado
$pagina = $url[1];

#ROTAS DE REDIRECIONAMENTO
//redirecionar para pagina informada
if (isset($pagina)) {
    $objController = new Controller();
    $objController->redirecionar($pagina);
}


//Auteticação
if (isset($_POST['login'])) {
    $objController = new Controller;
    $user = htmlspecialchars($_POST['user']);
    $senha = htmlspecialchars($_POST['senha']);

    //var_dump ($user);
    //var_dump ($senha);

    $objController->login_class($user, $senha);
}

//usuario
if (isset($_POST['cadastroUsuario'])) {
    //instaciar controller 
    $objController =  new Controller();

    // dados
    $nomeCompleto = htmlspecialchars($_POST['nomeCompleto']);
    $email = htmlspecialchars($_POST['email']);
    $cpf = htmlspecialchars($_POST['cpf']);
    $user = htmlspecialchars($_POST['user']);
    $senha = htmlspecialchars($_POST['senha']);
    $telefone = htmlspecialchars($_POST['telefone']);
    $id_tipo = $_POST['id_tipo'];
    $id_status_func = $_POST['id_status_func'];

    // Upload da imagem
    $foto = null;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nomeArquivo = uniqid() . '.' . $extensao;
        $caminhoDestino = 'uploads/' . $nomeArquivo;

        if (!is_dir('uploads')) {
            mkdir('uploads', 0755, true);
        }

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminhoDestino)) {
            $foto = $nomeArquivo;
        } else {
            echo "Erro ao salvar a imagem.";
        }
    }

    //invocar o método de validar
    $objController->inserir_usuarios($nomeCompleto, $cpf, $user, $senha, $telefone, $id_tipo, $id_status_func, $foto, $id_usuario, $email);
}

// 

//if (isset())

// Exibir usuários (consulta)
if (isset($_POST['consultar_usuario'])) {
    if (!isset($objController)) {
        $objController = new Controller();
    }

    $nomeUsuario = isset($_POST['nomeUsuario']) ? htmlspecialchars(trim($_POST['nomeUsuario'])) : null;

    $objController->mostrar_usuario($nomeUsuario);
}


//método excluir usuario
if (isset($_POST['excluir_usuario'])) {

    $objController = new Controller();
    $id_usuario = htmlspecialchars($_POST['id_usuario']);
    $objController->excluir_usuario($id_usuario);
}

//METODO ALTERAR USUARIO
if (isset($_POST['alterar_usuario'])) {
    $objController = new Controller();

    $id_usuario = $_POST['id_usuario'];
    $nomeCompleto = htmlspecialchars($_POST['nomeCompleto']);
    $email = htmlspecialchars($_POST['email']);
    $cpf = htmlspecialchars($_POST['cpf']);
    $user = htmlspecialchars($_POST['user']);
    $senha = htmlspecialchars($_POST['senha']);
    $telefone = htmlspecialchars($_POST['telefone']);
    $id_tipo = $_POST['id_tipo'];
    $id_status_func = $_POST['id_status_func'];

    $foto = null;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nomeArquivo = uniqid() . '.' . $extensao;
        $caminhoDestino = 'uploads/' . $nomeArquivo;

        if (!is_dir('uploads')) {
            mkdir('uploads', 0755, true);
        }

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminhoDestino)) {
            $foto = $nomeArquivo;
        } else {
            echo "Erro ao salvar a imagem.";
        }
    }



    $objController->alterar_usuario(
        $nomeCompleto,
        $senha,
        $telefone,
        $id_tipo,
        $id_status_func,
        $foto,
        $email,
        $id_usuario
    );
}

//MOTORISTA


if (isset($_POST['cadastrar_motorista'])) {
    $objController = new Controller();

    $id_usuario = $_POST['id_usuario'];
    $cnh = htmlspecialchars($_POST['cnh']);
    $categoria = htmlspecialchars($_POST['categoria']);
    $validade_cnh = htmlspecialchars($_POST['validade_cnh']);

    print_r($id_usuario) . '<BR> <BR>';
    print_r($cnh) . '<BR>';
    print_r($categoria) . '<BR>';
    print_r($validade_cnh) . '<BR>';

    $objController->inserir_motorista($id_usuario, $cnh, $categoria, $validade_cnh);
}


// Exibir Motorista (consulta)
if (isset($_POST['consultar_motorista'])) {
    if (!isset($objController)) {
        $objController = new Controller();
    }

    $nomeUsuario = isset($_POST['nomeMotorista']) ? htmlspecialchars(trim($_POST['nomeMotorista'])) : null;

    $objController->mostrar_motorista($nomeUsuario);
}


if (isset($_POST['excluir_Motorista'])) {
    $objController = new Controller();
    $id_Motorista = htmlspecialchars($_POST['id_Motorista']);
    var_dump($id_Motorista);
    $objController->excluir_Motorista($id_Motorista);
}

//METODO ALTERAR USUARIO
if (isset($_POST['alterar_motorista'])) {
    $objController = new Controller();

    $id_motorista = ($_POST['id_motorista']);
    $cnh = htmlspecialchars($_POST['cnh']);
    $validade_cnh = htmlspecialchars($_POST['validade_cnh']);
    $categoria = htmlspecialchars($_POST['categoria']);

    $objController->alterar_Motorista($id_motorista, $cnh, $categoria, $validade_cnh);
}




//VEICULO
if (isset($_POST['cadastroVeiculo'])) {

    $objController = new Controller();

    $placa = strtoupper($_POST['placa']);
    $modelo = ($_POST['modelo']);
    $ano = htmlspecialchars($_POST['ano']);
    $marca = htmlspecialchars($_POST['marca']);
    $cor = htmlspecialchars($_POST['cor']);
    $status_veiculo = htmlspecialchars($_POST['status_veiculo']);
    $observacoes = htmlspecialchars($_POST['observacoes']);

    $objController->inserirVeiculo($placa, $modelo, $ano, $marca, $cor, $status_veiculo, $observacoes);
}

if (isset($_POST['consultar_veiculo'])) {
    $objController = new Controller();
    $placaVeiculo = ($_POST['placaVeiculo']);

    $objController->mostrarVeiculo($placaVeiculo);
}

//método excluir veiculo
if (isset($_POST['excluirVeiculo'])) {

    $objController = new Controller();
    $id_veiculo = htmlspecialchars($_POST['id_veiculo']);
    $objController->excluir_veiculo($id_veiculo);
}


//METODO ALTERAR VEICULO
if (isset($_POST['alterar_veiculo'])) {
    $objController = new Controller();

    $id_veiculo = $_POST['id_veiculo'];
    $modelo = htmlspecialchars($_POST['modelo']);
    $marca = htmlspecialchars($_POST['marca']);
    $ano = htmlspecialchars($_POST['ano']);
    $cor = htmlspecialchars($_POST['cor']);
    $tipo = htmlspecialchars($_POST['status_veiculo']);

    $objController->alterar_veiculo($modelo, $marca, $cor, $ano, $status_veiculo, $id_veiculo);
}

//PEDIDOS

if (isset($_POST['incluir_pedido'])) {

    $objController = new Controller();


    if (isset($_FILES['arquivo_xml']) && $_FILES['arquivo_xml']['error'] === UPLOAD_ERR_OK) {
        $caminhoTemporario = $_FILES['arquivo_xml']['tmp_name'];

        $xml  = simplexml_load_file($caminhoTemporario);

        if (isset($xml->NFe->infNFe)) {
            $remetente_cpf_cnpj = (string)$xml->NFe->infNFe->emit->CNPJ;
            $remetenteNome = (string)$xml->NFe->infNFe->emit->xNome;
            $remetente_cep = (string)$xml->NFe->infNFe->emit->enderEmit->CEP;
            $remetente_endereco = (string)$xml->NFe->infNFe->emit->enderEmit->xLgr;
            $remetente_numero = (string)$xml->NFe->infNFe->emit->enderEmit->nro;

            // Informações do Pedido / Nota
            $pedidoNumero = (string)$xml->NFe->infNFe->ide->nNF;
            $notaNumero = (string)$xml->NFe->infNFe->ide->nNF;
            $chaveNota = (string)$xml->protNFe->infProt->chNFe;

            // Destinatário
            $destinatarioCpfCnpj = (string)$xml->NFe->infNFe->dest->CNPJ;
            $destinatarioNome = (string)$xml->NFe->infNFe->dest->xNome;
            $destinatario_cep = (string)$xml->NFe->infNFe->dest->enderDest->CEP;
            $destinatario_endereco = (string)$xml->NFe->infNFe->dest->enderDest->xLgr;
            $destinatario_numero = (string)$xml->NFe->infNFe->dest->enderDest->nro;
        } else {
            echo "Não foi possível localizar a NFe no XML.";
        }
    }

    $status_rota = htmlspecialchars($_POST['status_pedido']);




    //upload do arquivo
    $arquivoNome = null;
    if (isset($_FILES['arquivo_nome']) && $_FILES['arquivo_nome']['error'] === UPLOAD_ERR_OK) {
        $arquivoTemp = $_FILES['arquivo_nome']['tmp_name'];
        $arquivoNome = $_FILES['arquivo_nome']['name'];
        $diretorioDestino = 'uploads/';
        $caminhoDestino = $diretorioDestino . basename($arquivoNome);

        if (move_uploaded_file($arquivoTemp, $caminhoDestino)) {
            $arquivoNome = basename($arquivoNome);
        } else {
            echo "Erro ao carregar o arquivo.";
        }
    }



    $objController->inserir_Pedido(
        $arquivoNome,
        $destinatario_endereco,
        $destinatario_numero,
        $destinatario_cep,
        $remetente_numero,
        $remetente_endereco,
        $remetente_cpf_cnpj,
        $remetenteNome,
        $pedidoNumero,
        $notaNumero,
        $chaveNota,
        $destinatarioCpfCnpj,
        $destinatarioNome,
        $remetente_cep,
        $status_rota
    );
}

//inserir

if (isset($_POST["consultar_pedido"])) {
    $objController = new Controller();

    $numeroNota = $_POST["numeroNota"];

    $objController->exibir_pedidos($numeroNota);
}


// ROTAS 


if (isset($_POST["inserirRotas"])) {

    $objController = new Controller();

    $tipo_rota = htmlspecialchars($_POST["tipo_rota"]);
    $nome_rota = htmlspecialchars($_POST["nome_rota"]);
    $origem = htmlspecialchars($_POST["origem"]);
    $destino = htmlspecialchars($_POST["destino"]);
    $previsao = htmlspecialchars($_POST["previsao"]);
    $data_saida = htmlspecialchars($_POST["data_saida"]);
    $motorista_id = htmlspecialchars($_POST["motorista_id"]);
    $veiculo_id = htmlspecialchars($_POST["veiculo_id"]);
    $observacoes = htmlspecialchars($_POST["observacoes"]);
    $status_rota = htmlspecialchars($_POST["status_rota"]);
    $distancia = htmlspecialchars($_POST["distancia"]);


    // Tratamento das chaves NF-e
    $chaves = array_filter(array_map('trim', explode("\n", $_POST['chaves_nfe'])));



    $objController->inserirRota(
        $tipo_rota,
        $nome_rota,
        $origem,
        $destino,
        $previsao,
        $data_saida,
        $motorista_id,
        $veiculo_id,
        $observacoes,
        $status_rota,
        $distancia,
        $chaves
    );

    $objController->status_pedidos($chaves,  $status_rota);
}






//RASTREIO 

if (isset($_POST["consultar_rastreio"])) {
    $objController =  new Controller();
    $pedido_id = htmlspecialchars($_POST["pedido_id"]);

    $objController->Rastrear_pedido($pedido_id);
}











// Exibir rotas (consulta)
if (isset($_POST['consultar_rotas'])) {
    if (!isset($objController)) {
        $objController = new Controller();
    }

    $numeroPedido =  isset($_POST['numeroPedido']);

    $objController->exibir_Rotas($numeroPedido);
}


if (isset($_POST['atualizar_status_rota'])) {

    $objController = new Controller();


    $status_rota = $_POST['tipo_rota'];
    $id_rota =  $_POST['id_rota'];

    $objController->alterRota($id_rota, $status_rota);
}


if (isset($_POST['atualizar_status_pedidos'])) {

    $status_pedido = htmlspecialchars($_POST['status_pedido']);

    if (isset($_FILES['comprovante_entrega']) && $_FILES['comprovante_entrega']['error'] == 0) {
        $extensao = pathinfo($_FILES['comprovante_entrega']['name'], PATHINFO_EXTENSION);
        $nomeArquivo = uniqid('comprovante_') . '.' . $extensao;
        $caminhoDestino = 'uploads/' . $nomeArquivo;

        if (!is_dir('uploads')) {
            mkdir('uploads', 0755, true);
        }

        if (move_uploaded_file($_FILES['comprovante_entrega']['tmp_name'], $caminhoDestino)) {
            $comprovante = $nomeArquivo;
        } else {
            echo "Erro ao salvar o comprovante.";
        }
    }
}
