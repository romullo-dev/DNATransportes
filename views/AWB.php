<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rastreamento de AWB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        #resultado {
            display: none;
        }
    </style>
</head>
<body>
    <br><br>

<div class="container mt-5">
    <h2 class="text-center">Rastreamento de AWB</h2>

    <form id="form-rastreio" class="mt-4">
        <div class="mb-3">
            <label for="codigo_awb" class="form-label">Digite o código AWB:</label>
            <input type="text" class="form-control" id="codigo_awb" name="codigo_awb" required placeholder="Ex: AWB123456">
        </div>

        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-primary">Rastrear</button>
        </div>
    </form>

    <div id="resultado" class="mt-4">
        <h4>Resultado do Rastreamento:</h4>
        <p id="status">Carregando...</p>
        <p id="localizacao">Localização: Carregando...</p>
        <p id="data_atualizacao">Última Atualização: Carregando...</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const rastrearAWB = (codigoAWB) => {
        const rastreios = {
            "AWB123456": {
                status: "Saiu do centro de distribuição",
                localizacao: "Centro de Distribuição A",
                data_atualizacao: "2025-04-13 08:00:00"
            },
            "AWB789101": {
                status: "Em trânsito",
                localizacao: "Rodovia X - Rota Y",
                data_atualizacao: "2025-04-13 10:15:00"
            },
            "AWB112233": {
                status: "Entregue",
                localizacao: "Endereço de destino",
                data_atualizacao: "2025-04-12 15:30:00"
            }
        };

        if (rastreios[codigoAWB]) {
            return rastreios[codigoAWB];
        } else {
            return null; 
        }
    };

    document.getElementById('form-rastreio').addEventListener('submit', function(event) {
        event.preventDefault();

        const codigoAWB = document.getElementById('codigo_awb').value.trim();
        const resultadoDiv = document.getElementById('resultado');
        
        document.getElementById('status').innerText = 'Carregando...';
        document.getElementById('localizacao').innerText = 'Localização: Carregando...';
        document.getElementById('data_atualizacao').innerText = 'Última Atualização: Carregando...';

        const rastreio = rastrearAWB(codigoAWB);

        if (rastreio) {
            document.getElementById('status').innerText = `Status: ${rastreio.status}`;
            document.getElementById('localizacao').innerText = `Localização: ${rastreio.localizacao}`;
            document.getElementById('data_atualizacao').innerText = `Última Atualização: ${rastreio.data_atualizacao}`;
        } else {
            document.getElementById('status').innerText = 'AWB não encontrado.';
            document.getElementById('localizacao').innerText = '';
            document.getElementById('data_atualizacao').innerText = '';
        }

        resultadoDiv.style.display = 'block';
    });
</script>

</body>
</html>
