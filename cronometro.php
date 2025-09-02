<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Estudos IA - Cronômetro</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: #fdfdfd;
      color: #333;
    }

    /* ===== Cabeçalho ===== */
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 40px;
      border-bottom: 2px solid #eee;
    }

    header h2 {
      color: #7d5ba6;
      font-weight: 600;
    }

    header nav {
      display: flex;
      align-items: center;
      gap: 20px;
    }

    header nav a {
      color: #333;
      text-decoration: none;
      font-weight: 500;
    }

    .usuario {
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: 500;
    }

    .usuario-icon {
      width: 35px;
      height: 35px;
      border-radius: 50%;
      border: 2px solid #7d5ba6;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 18px;
      color: #7d5ba6;
    }

    /* ===== Layout principal ===== */
    .container {
      display: grid;
      grid-template-columns: 250px 1fr 250px;
      gap: 30px;
      padding: 40px;
      align-items: center;
    }

    /* ===== Cards laterais (à esquerda) ===== */
    .cards {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .card {
      background: #c49ad8;
      color: white;
      border-radius: 15px;
      padding: 20px;
      text-align: center;
    }

    .card .tempo {
      font-size: 1.8rem;
      font-weight: 700;
    }

    .card p {
      margin-top: 5px;
      font-size: 0.9rem;
    }

    /* ===== Gato e cronômetro ===== */
    .cronometro {
      text-align: center;
    }

    .cronometro h1 {
      font-size: 1.6rem;
      font-weight: 700;
    }

    .cronometro h3 {
      color: #a06c9f;
      margin-bottom: 20px;
    }

    .cronometro img {
      width: 350px;
      margin-bottom: 15px;
    }

    .botao {
      display: inline-block;
      background: linear-gradient(135deg, #7d5ba6, #a06c9f);
      color: white;
      padding: 12px 30px;
      border-radius: 25px;
      font-size: 1rem;
      font-weight: 600;
      text-decoration: none;
      border: none;
      cursor: pointer;
      transition: transform 0.2s ease;
    }

    .botao:hover {
      transform: scale(1.05);
    }

    /* ===== Amigos à direita ===== */
    .amigos {
      border: 2px solid #c49ad8;
      border-radius: 15px;
      padding: 20px;
      text-align: center;
    }

    .amigos h3 {
      color: #333;
      margin-bottom: 15px;
    }

    .lista-amigos {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 15px;
      justify-items: center;
    }

    .amigo {
      width: 55px;
      height: 55px;
      border-radius: 50%;
      border: 2px solid #7d5ba6;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 22px;
      color: #7d5ba6;
    }
  </style>
</head>
<body>

  <!-- Cabeçalho -->
  <header>
    <h2>Estudos IA</h2>
    <nav>
      <a href="#">Sobre nós</a>
      <div class="usuario">
        <span>Olá, usuário!</span>
        <div class="usuario-icon">👤</div>
      </div>
    </nav>
  </header>

  <!-- Corpo principal -->
  <div class="container">
    <!-- Cards laterais -->
    <div class="cards">
      <div class="card">
        <div class="tempo">00:00</div>
        <p>Horas Estudadas</p>
      </div>
      <div class="card">
        <div class="tempo">00:00</div>
        <p>Maior Hora</p>
      </div>
      <div class="card">
        <div class="tempo">00:00</div>
        <p>Horas Estudadas Hoje</p>
      </div>
    </div>

    <!-- Cronômetro e gatinho -->
    <div class="cronometro">
      <h1>CONOMETRE SEUS ESTUDOS</h1>
      <h3>Método Pomodoro</h3>
      <img src="imagens/gatinho.png" alt="Gatinho fofo estudando">
      <br>
      <button class="botao">Iniciar</button>
    </div>

    <!-- Amigos -->
    <div class="amigos">
      <h3>Estudando com você</h3>
      <div class="lista-amigos">
        <div class="amigo">👤</div>
        <div class="amigo">👤</div>
        <div class="amigo">👤</div>
        <div class="amigo">👤</div>
      </div>
    </div>
  </div>

</body>
</html>
