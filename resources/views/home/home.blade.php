<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DNA Transporte e Logística</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-black text-white font-sans scroll-smooth">

  <!-- Menu -->
  <header class="fixed top-0 left-0 w-full flex justify-between items-center px-6 md:px-12 py-4 bg-black/80 backdrop-blur-md shadow-md z-50">
    <img src="{{ asset('images/fotocapa.jpg') }}" alt="Logo DNA Transporte" class="h-12 w-auto rounded-md">
    <h1 class="text-xl md:text-2xl font-bold text-yellow-400 tracking-wide">DNA Transporte & Logística</h1>
    <nav>
      <ul class="flex space-x-6 text-sm md:text-lg">
        <li><a href="#home" class="hover:text-yellow-400 transition">Início</a></li>
        <li><a href="#sobre" class="hover:text-yellow-400 transition">Sobre</a></li>
        <li><a href="#servicos" class="hover:text-yellow-400 transition">Serviços</a></li>
        <li><a href="#contato" class="hover:text-yellow-400 transition">Contato</a></li>
        <li>
            <a href="{{ route('login') }}" class="hover:text-yellow-400 transition">
              Login
            </a>
          </li>
          
      </ul>
    </nav>
  </header>

  <!-- Hero -->
<section id="home" class="relative h-screen flex items-center justify-center pt-20">
  <img src="{{ asset('images/fotocapa.jpg') }}" alt="Caminhão DNA Transporte" class="absolute inset-0 w-full h-full object-cover opacity-50">
  <div class="relative z-10 text-center px-6">
    <h2 class="text-4xl md:text-6xl font-extrabold text-yellow-400 drop-shadow-lg animate-pulse">
      Soluções Inteligentes em Transporte e Logística
    </h2>

    <!-- Frase melhorada com efeito glow -->
    <p class="mt-4 text-2xl md:text-3xl text-white font-bold italic glow-text opacity-0 animate-fadeIn">
      O hoje constrói o amanhã
    </p>

    <p class="mt-6 text-lg md:text-xl text-gray-200 max-w-2xl mx-auto">
      Conectando pessoas e empresas com eficiência, segurança e rapidez.
    </p>

    <a href="#contato" class="mt-8 inline-block px-8 py-4 bg-yellow-400 text-black font-bold rounded-lg shadow-lg hover:bg-yellow-500 hover:scale-105 transition-transform duration-300">
      Solicite um Orçamento
    </a>
  </div>
</section>

<!-- Estilos para animação -->
<style>
  /* Glow pulsante */
  .glow-text {
    text-shadow: 0 0 10px #FFD700, 0 0 20px #FFD700, 0 0 30px #FFD700, 0 0 40px #FFD700;
    animation: glowPulse 2s infinite alternate;
  }

  @keyframes glowPulse {
    0% { text-shadow: 0 0 5px #FFD700, 0 0 10px #FFD700, 0 0 15px #FFD700, 0 0 20px #FFD700; }
    100% { text-shadow: 0 0 20px #FFD700, 0 0 40px #FFD700, 0 0 60px #FFD700, 0 0 80px #FFD700; }
  }

  /* Fade-in suave */
  .animate-fadeIn {
    animation: fadeIn 2s forwards 0.5s; /* delay 0.5s */
  }

  @keyframes fadeIn {
    0% { opacity: 0; transform: translateY(20px); }
    100% { opacity: 1; transform: translateY(0); }
  }
</style>


  <!-- Sobre -->
  <section id="sobre" class="py-20 px-6 md:px-16 text-center">
    <h3 class="text-3xl font-bold text-yellow-400">Quem Somos</h3>
    <p class="mt-6 max-w-3xl mx-auto text-gray-300 leading-relaxed">
      A <span class="text-yellow-400 font-semibold">DNA Transporte e Logística</span> atua no setor de transporte rodoviário de cargas, oferecendo serviços completos de logística integrada. 
      Nosso compromisso é garantir soluções rápidas, seguras e personalizadas para cada cliente, contribuindo para o crescimento dos negócios e o fortalecimento das parcerias.
    </p>
  </section>

  <!-- Serviços -->
  <section id="servicos" class="py-20 px-6 md:px-16 bg-gray-900 text-center">
    <h3 class="text-3xl font-bold text-yellow-400">Nossos Serviços</h3>
    <div class="mt-12 grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
      <div class="p-8 bg-black/50 rounded-xl shadow-lg hover:scale-105 transition-transform duration-300">
        <h4 class="text-xl font-semibold text-yellow-400">Transporte Rodoviário</h4>
        <p class="mt-3 text-gray-300">Cargas fracionadas e dedicadas com segurança e agilidade em todo o Brasil.</p>
      </div>
      <div class="p-8 bg-black/50 rounded-xl shadow-lg hover:scale-105 transition-transform duration-300">
        <h4 class="text-xl font-semibold text-yellow-400">Logística Integrada</h4>
        <p class="mt-3 text-gray-300">Planejamento, armazenagem e distribuição com foco em eficiência.</p>
      </div>
      <div class="p-8 bg-black/50 rounded-xl shadow-lg hover:scale-105 transition-transform duration-300">
        <h4 class="text-xl font-semibold text-yellow-400">Consultoria Personalizada</h4>
        <p class="mt-3 text-gray-300">Soluções sob medida para cada tipo de operação logística.</p>
      </div>
    </div>
  </section>

  <!-- Contato -->
  <section id="contato" class="py-20 px-6 md:px-16 text-center">
    <h3 class="text-3xl font-bold text-yellow-400">Fale Conosco</h3>
    <p class="mt-6 text-gray-300">Entre em contato conosco pelo e-mail ou WhatsApp:</p>
    <a href="mailto:dnatransporte@gmail.com" class="mt-4 block text-yellow-400 text-xl font-semibold hover:underline">
      dnatransporte@gmail.com
    </a>
    <a href="https://wa.me/5599999999999" target="_blank" class="mt-2 block text-green-400 text-xl font-semibold hover:underline">
      WhatsApp: (99) 99999-9999
    </a>
  </section>

  <!-- Rodapé -->
  <footer class="bg-black py-8 text-center text-gray-400 border-t border-gray-800">
    <p class="mb-4">&copy; 2025 DNA Transporte e Logística. Todos os direitos reservados.</p>
    <p class="mb-4 italic text-yellow-400 font-semibold">O hoje constrói o amanhã</p>
    <div class="flex justify-center space-x-6 text-2xl">
      <a href="https://facebook.com" target="_blank" class="hover:text-yellow-400"><i class="ph ph-facebook-logo"></i></a>
      <a href="https://instagram.com" target="_blank" class="hover:text-yellow-400"><i class="ph ph-instagram-logo"></i></a>
      <a href="https://linkedin.com" target="_blank" class="hover:text-yellow-400"><i class="ph ph-linkedin-logo"></i></a>
      <a href="https://youtube.com" target="_blank" class="hover:text-yellow-400"><i class="ph ph-youtube-logo"></i></a>
      <a href="https://tiktok.com" target="_blank" class="hover:text-yellow-400"><i class="ph ph-tiktok-logo"></i></a>
      <a href="https://twitter.com" target="_blank" class="hover:text-yellow-400"><i class="ph ph-twitter-logo"></i></a>
    </div>
  </footer>

  <!-- Estilos do botão flutuante -->
  <style>
    .btnZap {
      position: fixed;
      left: 20px;
      bottom: 20px;
      z-index: 9999;
      cursor: pointer;
      width: 60px;
      height: 60px;
      background-color: #25D366;
      border-radius: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 10px rgba(0,0,0,0.3);
      animation: whatsapp-pulse 2s infinite;
    }
    .btnZap img { width: 35px; height: 35px; }
    @keyframes whatsapp-pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.1); }
      100% { transform: scale(1); }
    }
  </style>

  <!-- Botão WhatsApp flutuante -->
  <div class="btnZap" onclick="abrirWhatsApp()" aria-label="Abrir WhatsApp">
    <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
  </div>

  <!-- Modal de Login -->
  <div id="loginModal" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-[10000]">
    <div class="bg-[#293241] p-8 rounded-xl w-96 shadow-2xl relative border border-[#3e4754]">
      <button onclick="fecharLogin()" class="absolute top-3 right-3 text-gray-400 hover:text-white">✖</button>
      <h3 class="text-2xl font-bold text-yellow-400 mb-6 text-center">Login</h3>
      <form onsubmit="return fazerLogin(event)">
        <input type="email" placeholder="E-mail" required class="w-full p-3 mb-4 rounded-lg bg-[#3e4754] text-white focus:outline-none focus:ring-2 focus:ring-yellow-400">
        <input type="password" placeholder="Senha" required class="w-full p-3 mb-6 rounded-lg bg-[#3e4754] text-white focus:outline-none focus:ring-2 focus:ring-yellow-400">
        <button type="submit" class="w-full py-3 bg-yellow-400 text-black font-bold rounded-lg hover:bg-yellow-500 transition">Entrar</button>
      </form>
    </div>
  </div>

  <!-- Scripts -->
  <script>
    function abrirWhatsApp() {
      const numero = '5511999999999';
      const mensagem = encodeURIComponent('Olá, gostaria de mais informações.');
      const url = https://api.whatsapp.com/send?phone=${numero}&text=${mensagem};
      window.open(url, '_blank');
    }

    function abrirLogin() {
      document.getElementById('loginModal').classList.remove('hidden');
    }
    function fecharLogin() {
      document.getElementById('loginModal').classList.add('hidden');
    }
    function fazerLogin(event) {
      event.preventDefault();
      alert("Login enviado! (Aqui você conecta com backend/Firebase)");
      fecharLogin();
      return false;
    }
  </script>

</body>
</html>