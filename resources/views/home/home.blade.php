<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DNA Transporte e Logística</title>
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Ícones Phosphor -->
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
  <style>
    /* Carrossel */
    .carousel img {
      filter: brightness(0.75); /* menos escuro */
      height: 100vh;
      object-fit: cover;
    }

    /* Glow no texto */
    .glow-text {
      text-shadow: 0 0 10px #FFC107;
    }

    /* Hover redes sociais */
    .social-icon:hover {
      transform: scale(1.2);
      color: #FFC107 !important;
      transition: all 0.3s;
    }

    /* Botão seta */
    .scroll-down {
      position: absolute;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      font-size: 2rem;
      color: #FFC107;
      cursor: pointer;
    }
    .bounce {
      animation: bounce 1.5s infinite;
    }
    @keyframes bounce {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(10px); }
    }
  </style>
</head>

<body class="bg-black text-white">

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-black fixed-top shadow-sm">
    <div class="container-fluid px-4">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="{{ asset('images/logo2.jpg') }}" alt="Logo DNA Transporte" class="me-2 rounded" style="height: 50px;">
        <span class="fw-bold fs-4 glow-text">DNA Transporte</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav gap-3">
          <li class="nav-item"><a class="nav-link" href="#home">Início</a></li>
          <li class="nav-item"><a class="nav-link" href="#sobre">Sobre</a></li>
          <li class="nav-item"><a class="nav-link" href="#servicos">Serviços</a></li>
          <li class="nav-item"><a class="nav-link" href="#localizacao">Localização</a></li>
          <li class="nav-item"><a class="nav-link" href="#contato">Contato</a></li>
          <li class="nav-item"><a class="nav-link text-warning" href="{{ route('login') }}">Login</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Carrossel -->
  <section id="home" class="position-relative">
    <div id="dnaCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="{{ asset('images/fotocapa2.jpg') }}" class="d-block w-100" alt="Capa 1">
        </div>
        <div class="carousel-item">
          <!--<img src="{{ asset('images/fotocapa2.jpg') }}" class="d-block w-100" alt="Capa 2">-->
        </div>
      </div>
    </div>

    <!-- Redes sociais no desktop -->
    <div class="position-absolute top-50 end-0 translate-middle-y d-none d-lg-flex flex-column gap-3 me-3">
      <a href="#" class="text-white fs-3 social-icon"><i class="ph ph-facebook-logo"></i></a>
      <a href="#" class="text-white fs-3 social-icon"><i class="ph ph-instagram-logo"></i></a>
      <a href="#" class="text-white fs-3 social-icon"><i class="ph ph-linkedin-logo"></i></a>
      <a href="#" class="text-white fs-3 social-icon"><i class="ph ph-youtube-logo"></i></a>
      <a href="#" class="text-white fs-3 social-icon"><i class="ph ph-tiktok-logo"></i></a>
      <a href="#" class="text-white fs-3 social-icon"><i class="ph ph-twitter-logo"></i></a>
    </div>

    <!-- Botão seta para baixo -->
    <a href="#texto" class="scroll-down bounce">
      <i class="ph ph-arrow-down"></i>
    </a>
  </section>

  <!-- Texto abaixo do carrossel -->
  <section id="texto" class="text-center py-5 bg-black">
    <div class="container">
      <h2 class="display-5 fw-bold glow-text">Soluções Inteligentes em Transporte e Logística</h2>
      <p class="mt-3 fs-5 text-secondary">
        Conectando pessoas e empresas com eficiência, segurança e rapidez.
      </p>
      <a href="#contato" class="btn btn-warning btn-lg mt-4 fw-bold shadow">Solicite um Orçamento</a>
    </div>
  </section>

  <!-- Sobre -->
  <section id="sobre" class="py-5 text-center">
    <div class="container">
      <h3 class="text-warning fw-bold">Quem Somos</h3>
      <p class="mt-3 text-secondary">
        A <span class="text-warning fw-semibold">DNA Transporte e Logística</span> atua no setor de transporte rodoviário de cargas, oferecendo serviços completos de logística integrada.
        Nosso compromisso é garantir soluções rápidas, seguras e personalizadas para cada cliente.
      </p>
    </div>
  </section>

  <!-- Serviços -->
  <section id="servicos" class="py-5 bg-dark text-center">
    <div class="container">
      <h3 class="text-warning fw-bold">Nossos Serviços</h3>
      <div class="row mt-4 g-4">
        <div class="col-md-4">
          <div class="p-4 bg-black rounded shadow h-100">
            <h4 class="text-warning">Transporte Rodoviário</h4>
            <p class="text-secondary">Cargas fracionadas e dedicadas com segurança e agilidade.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="p-4 bg-black rounded shadow h-100">
            <h4 class="text-warning">Logística Integrada</h4>
            <p class="text-secondary">Planejamento, armazenagem e distribuição eficientes.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="p-4 bg-black rounded shadow h-100">
            <h4 class="text-warning">Consultoria Personalizada</h4>
            <p class="text-secondary">Soluções sob medida para cada operação logística.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Localização -->
  <section id="localizacao" class="py-5 text-center bg-black">
    <div class="container">
      <h3 class="text-warning fw-bold">Nossa Localização</h3>
      <p class="text-secondary">Venha nos visitar, estamos em um ponto estratégico para atender você com rapidez.</p>
      <div class="mt-4 rounded shadow overflow-hidden border border-warning">
        <iframe  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3658.871236329856!2d-46.64114628498049!3d-23.5080908847097!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94ce59c86e8f9b0b%3A0xa4a3a842a7b1c9f2!2sSão%20Paulo%20-%20SP!5e0!3m2!1spt-BR!2sbr!4v1700000000000!5m2!1spt-BR!2sbr"  width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
      </div>
    </div>
  </section>

  <!-- Contato -->
  <section id="contato" class="py-5 text-center">
    <div class="container">
      <h3 class="text-warning fw-bold">Fale Conosco</h3>
      <form class="mt-4 bg-dark p-4 rounded shadow mx-auto" style="max-width:600px;">
        <input type="text" placeholder="Nome" class="form-control mb-3 bg-amber-100 text-white border-secondary">
        <input type="email" placeholder="E-mail" class="form-control mb-3 bg-amber-100 text-white border-secondary">
        <input type="tel" placeholder="Telefone" class="form-control mb-3 bg-amber-100 text-white border-secondary">
        <textarea placeholder="Mensagem" rows="4" class="form-control mb-3 bg-amber-100 text-white border-secondary"></textarea>
        <button class="btn btn-warning w-100 fw-bold">Enviar</button>
      </form>
      <p class="mt-3"><a href="mailto:dnatransporte@gmail.com" class="text-warning">dnatransporte@gmail.com</a></p>
      <p><a href="https://wa.me/5599999999999" class="text-success">WhatsApp: (99) 99999-9999</a></p>
    </div>
  </section>

  <!-- Rodapé -->
  <footer class="bg-black py-4 text-center text-secondary border-top border-dark">
    <p class="mb-2">&copy; 2025 DNA Transporte e Logística. Todos os direitos reservados.</p>
    <p class="mb-3 text-warning glow-text">O hoje constrói o amanhã</p>
    <div class="d-flex justify-content-center gap-3 fs-4">
      <a href="#" class="text-white social-icon"><i class="ph ph-facebook-logo"></i></a>
      <a href="#" class="text-white social-icon"><i class="ph ph-instagram-logo"></i></a>
      <a href="#" class="text-white social-icon"><i class="ph ph-linkedin-logo"></i></a>
      <a href="#" class="text-white social-icon"><i class="ph ph-youtube-logo"></i></a>
      <a href="#" class="text-white social-icon"><i class="ph ph-tiktok-logo"></i></a>
      <a href="#" class="text-white social-icon"><i class="ph ph-twitter-logo"></i></a>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
