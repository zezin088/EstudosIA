<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Estudos IA</title>
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Roboto', sans-serif;
      background: white;
      color: #333;
      line-height: 1.6;
    }

    /* Header */
    header {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      background: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(6px);
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      z-index: 1000;
      border-bottom: 1px solid #bdebe3;
    }

    .logo {
      font-family: 'Pacifico', cursive;
      font-size: 1.8rem;
      color: #3f7c72;
    }

    nav {
      display: flex;
      align-items: center;
      gap: 1.5rem;
    }

    nav a {
      text-decoration: none;
      color: #3f7c72;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 0.4rem;
      transition: color 0.3s ease;
    }

    nav a:hover {
      color: #2a5c55;
    }

    /* Botão */
    .btn {
      display: inline-block;
      padding: 0.6rem 1.4rem;
      background: #3f7c72;
      color: white;
      border-radius: 25px;
      font-weight: 500;
      transition: 0.3s;
      text-decoration: none;
    }

    .btn:hover {
      background: #2a5c55;
    }

    /* Banner */
    .banner {
      background: #3f7c72;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 3rem ;
      margin-top: 70px; /* espaço para o header fixo */
      border-radius: 0;
    }

    .banner-texto {
      max-width: 500px;
      color: white;
    }

    .banner h1 {
      font-family: 'Pacifico', cursive;
      font-size: 2.5rem;
      margin-bottom: 1rem;
    }

    .banner p {
      font-size: 1.1rem;
      margin-bottom: 1.5rem;
    }

    .banner .btn {
      background: white;
      color: #3f7c72;
      font-weight: bold;
    }

    .banner .btn:hover {
      background: #bdebe3;
      color: #2a5c55;
    }

    .banner-img img {
      width: 250px;
      max-width: 100%;
      border-radius: 12px;
    }

    /* Seções */
    section {
      padding: 4rem 2rem;
      max-width: 1200px;
      margin: 0 auto;
    }

    section h2 {
      font-size: 2rem;
      text-align: center;
      color: #3f7c72;
      margin-bottom: 2rem;
    }

    /* Cards */
    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
    }

    .card {
      background: #fff;
      border-radius: 20px;
      padding: 2rem;
      text-align: center;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      border: 1px solid #bdebe3;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      text-decoration: none;
      color: inherit;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }

    .card i {
      font-size: 2rem;
      color: #3f7c72;
      margin-bottom: 1rem;
    }

    .card h3 {
      color: #3f7c72;
      margin-bottom: 1rem;
    }

    /* Cronômetro fixo */
    .cronometro {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: #fff;
      border: 2px solid #bdebe3;
      border-radius: 15px;
      padding: 1rem;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      text-align: center;
      z-index: 1000;
      width: 160px;
    }

    .cronometro h4 {
      margin-bottom: 0.5rem;
      color: #3f7c72;
    }

    .cronometro button {
      margin: 0.3rem;
      padding: 0.4rem 0.8rem;
      border: none;
      border-radius: 8px;
      background: #3f7c72;
      color: white;
      cursor: pointer;
      transition: 0.3s;
    }

    .cronometro button:hover {
      background: #2a5c55;
    }

    /* Footer */
    footer {
      background: #3f7c72;
      color: white;
      text-align: center;
      padding: 2rem;
      margin-top: 3rem;
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header>
    <div class="logo">Estudos IA</div>
    <nav>
      <a href="editar_usuario.php"><i class="fa-solid fa-user"></i></a>
      <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Sair</a>
    </nav>
  </header>

  <!-- Banner -->
  <section class="banner">
    <div class="banner-texto">
      <h1>Bem-vindo ao Estudos IA</h1>
      <p>Seu espaço inteligente para aprender, se organizar e evoluir</p>
      <a href="#funcoes" class="btn">Explorar Funções</a>
    </div>
    <div class="banner-img">
      <img src="https://i.pinimg.com/originals/a0/ce/6b/a0ce6ba41bf31c32fbced60d9070b0fe.gif" alt="Robôzinho IA">
    </div>
  </section>

  <!-- Funções -->
  <section id="funcoes">
    <h2>Funções Principais</h2>
    <div class="cards">
      <a href="anotacoes.php" class="card">
        <i class="fa-solid fa-pen-to-square"></i>
        <h3>Anotações</h3>
        <p>Crie e organize suas anotações de estudo de forma prática.</p>
      </a>
      <a href="flashcards.php" class="card">
        <i class="fa-solid fa-clone"></i>
        <h3>Flashcards</h3>
        <p>Revise conteúdos com cartões interativos para melhorar sua memória.</p>
      </a>
      <a href="plano_estudos.php" class="card">
        <i class="fa-solid fa-calendar-days"></i>
        <h3>Plano de Estudos</h3>
        <p>Monte seu cronograma personalizado e nunca perca prazos.</p>
      </a>
    </div>
  </section>

  <!-- Rede Social -->
  <section id="social">
    <h2>Rede Social</h2>
    <div class="cards">
      <a href="rede.php" class="card">
        <i class="fa-solid fa-users"></i>
        <h3>Comunidade</h3>
        <p>Conecte-se, compartilhe conquistas e troque experiências.</p>
      </a>
      <a href="feed.php" class="card">
        <i class="fa-solid fa-comments"></i>
        <h3>Interação</h3>
        <p>Participe do feed, curta, comente e incentive outros estudantes.</p>
      </a>
    </div>
  </section>

  <!-- Cronômetro fixo -->
  <div class="cronometro">
    <h4>Cronômetro</h4>
    <div id="tempo">00:00:00</div>
    <button onclick="iniciar()">Iniciar</button>
    <button onclick="pausar()">Pausar</button>
    <button onclick="zerar()">Zerar</button>
  </div>

  <!-- Footer -->
  <footer>
    <p>&copy; 2025 Estudos IA. Todos os direitos reservados.</p>
  </footer>

  <!-- Script Cronômetro -->
  <script>
    let segundos = 0, minutos = 0, horas = 0;
    let intervalo;

    function doisDigitos(digito) {
      return digito < 10 ? '0' + digito : digito;
    }

    function atualizar() {
      document.getElementById('tempo').innerText =
        doisDigitos(horas) + ":" + doisDigitos(minutos) + ":" + doisDigitos(segundos);
    }

    function iniciar() {
      if (!intervalo) {
        intervalo = setInterval(() => {
          segundos++;
          if (segundos == 60) { segundos = 0; minutos++;
            if (minutos == 60) { minutos = 0; horas++; }
          }
          atualizar();
        }, 1000);
      }
    }

    function pausar() {
      clearInterval(intervalo);
      intervalo = null;
    }

    function zerar() {
      clearInterval(intervalo);
      intervalo = null;
      segundos = 0; minutos = 0; horas = 0;
      atualizar();
    }

    atualizar();
  </script>
</body>
</html>
