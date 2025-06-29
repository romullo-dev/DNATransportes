


<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  
  <title>Home - Sistema Logística</title>

  <style>
    body {
      background: linear-gradient(120deg, #1e3c72, #2a5298);
      color: white;
      font-family: 'Arial', sans-serif;
    }

    .content {
      margin-top: 100px;
    }

    .page-title {
      text-align: center;
      font-size: 2.5rem;
      margin-bottom: 40px;
      font-weight: bold;
      color: #f39c12;
    }

    .card-deck {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
    }

    .card {
      background-color: rgba(255, 255, 255, 0.1);
      border: none;
      border-radius: 15px;
      width: 18rem;
      margin: 15px;
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: scale(1.05);
    }

    .btn-custom {
      background-color: #f39c12;
      color: white;
      font-size: 1.2rem;
      padding: 15px 30px;
      border-radius: 50px;
    }

    .btn-custom:hover {
      background-color: #e67e22;
    }
  </style>
</head>

<body>

<header>
    <?php include 'templates/menu.php'; ?>
</header>


  <main>
    <div class="content">
    <h1 class="page-title">Bem-vindo à DNA Transportes</h1>

    <div class="card-deck">
      <div class="card">
        <div class="card-body text-center">
          <h5 class="card-title">Rastrear Encomenda</h5>
          <p class="card-text">Acesse o status do seu pedido em tempo real.</p>
          <a href="index.php?rastreio" class="btn btn-custom">Rastrear</a>
        </div>
      </div>

      <div class="card">
        <div class="card-body text-center">
          <h5 class="card-title">Pedidos</h5>
          <p class="card-text">Visualize os pedidos ativos e históricos.</p>
          <a href="index.php?pedidos" class="btn btn-custom">Ver Pedidos</a>
        </div>
      </div>

      <div class="card">
        <div class="card-body text-center">
          <h5 class="card-title">Cotação</h5>
          <p class="card-text">Simule o valor do seu frete de forma rápida.</p>
          <a href="index.php?cotacao" class="btn btn-custom">Cotação</a>
        </div>
      </div>
    </div>
  </div>

  </main>
<footer>
    <?php include 'templates/footer.php'; ?>
</footer>
  


</body>
</html>
