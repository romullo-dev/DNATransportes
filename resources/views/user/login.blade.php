<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - DNA Transporte & Logística</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-white font-sans flex items-center justify-center min-h-screen relative">

  <!-- Fundo com imagem -->
  <img src="{{ asset('images/fotocapa2.jpg') }}" alt="Caminhão DNA Transporte" class="absolute inset-0 w-full h-full object-cover opacity-40">

  <!-- Overlay escuro -->
  <div class="absolute inset-0 bg-black/60"></div>

  <!-- Container de login -->
  <div class="relative z-10 bg-black/90 backdrop-blur-md p-8 rounded-2xl shadow-2xl border border-gray-700 w-[90%] max-w-md text-center">
    <img src="{{ asset('images/logo2.jpg') }}" alt="Logo DNA Transportes" class="mx-auto mb-4 h-20 w-auto rounded-md shadow-lg">
    <h2 class="text-2xl md:text-3xl font-bold text-yellow-400 mb-6">Acesso ao Sistema</h2>

    @if ($errors->any())
      <div class="bg-red-500/20 border border-red-400 text-red-300 text-sm rounded-md p-3 text-left mb-4">
        <ul class="list-disc list-inside">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
      @csrf
      <div class="text-left">
        <label for="user" class="block text-sm font-semibold text-gray-300 mb-1">Usuário</label>
        <input type="text" id="user" name="user" value="{{ old('user') }}"
               class="w-full p-3 rounded-lg bg-[#3e4754] text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400"
               required autofocus>
      </div>

      <div class="text-left">
        <label for="password" class="block text-sm font-semibold text-gray-300 mb-1">Senha</label>
        <input type="password" id="password" name="password"
               class="w-full p-3 rounded-lg bg-[#3e4754] text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400"
               required>
      </div>

      <button type="submit"
              class="w-full py-3 mt-2 bg-yellow-400 text-black font-bold rounded-lg shadow-lg hover:bg-yellow-500 hover:scale-105 transition-transform duration-300">
        Entrar
      </button>
    </form>

    <p class="mt-6 text-sm text-gray-400">
      &copy; 2025 DNA Transporte & Logística
    </p>
  </div>

</body>
</html>
