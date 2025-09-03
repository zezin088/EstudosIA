<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

include 'conexao.php';

$id_usuario = $_SESSION['usuario_id'];
$sql = "SELECT nome, foto FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>EstudosIA</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    @font-face {
      font-family: 'Bungee';
      src: url('fonts/Bungee-Regular.ttf') format('truetype');
    }

    @font-face {
      font-family: 'Fredoka';
      src: url('fonts/Fredoka-Regular.ttf') format('truetype');
    }

    body {
      margin: 0;
      font-family: 'Arial', sans-serif;
      background-color: #ffffff;
    }

    .topo {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 40px;
      background-color: #f1f1f1;
    }

    .topo h1 { color: #2c2c54; font-size: 24px; }
    .topo nav { display: flex; align-items: center; gap: 30px; }

    .user-wrapper {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .user-wrapper img {
      width: 40px; height: 40px; border-radius: 50%;
      object-fit: cover; border: 2px solid #4a69bd;
    }
    .user-wrapper span { font-weight: bold; color: #4a69bd; }
    .logout-link { margin-left: 10px; text-decoration: none; color: #c0392b; font-weight: bold; }

    /* Banner */
    .banner {
      background-color: #9db4cc;
      text-align: center;
      padding: 16px 20px;
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 200px;
      overflow: visible;
    }

    .banner-text { z-index: 2; }

    .banner-icon {
      height: 260px; width: auto;
      position: absolute; top: 50%;
      transform: translateY(-50%);
      z-index: 1; filter: drop-shadow(0 6px 12px rgba(0,0,0,0.2));
      pointer-events: none;
    }
    .banner-icon.left { left: 24px; }
    .banner-icon.right { right: 24px; }

    .banner h2 {
      font-size: 48px; color: #2c2c54;
      margin: 0 0 8px 0; font-family: 'Bungee', cursive;
    }
    .banner h3 {
      font-size: 24px; color: #ffffff;
      font-style: italic; font-family: 'Fredoka', sans-serif; margin: 0;
    }
    .banner button {
      margin-top: 20px; padding: 14px 28px; font-size: 16px;
      background-color: #3c3c74; color: white; border: none;
      border-radius: 8px; cursor: pointer;
    }

.conteudo-principal {
  display: flex;
  flex-direction: row;   /* agora ficam lado a lado */
  align-items: flex-start;
  gap: 60px;             /* espaço entre cronômetro e quadrados */
  margin: 90px 90px;
}
    .botao-cronometro {
      width: 400px; height: 170px;
      background: none; border: none; cursor: pointer;
      transition: transform 0.2s ease;
      flex-shrink: 0;
    }
    .botao-cronometro:hover { transform: scale(1.06); }
    .botao-cronometro img { width: 100%; height: 100%; object-fit: contain; }

    .funcoes-container {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 40px;
      max-width: 800px;
    }
    .funcao {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 15px;
    }
    .icone {
      width: 120px; height: 120px;
      background-color: #cfe0f3; border-radius: 16px;
      display: flex; align-items: center; justify-content: center;
      overflow: hidden; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    .icone img { width: 90%; height: 90%; object-fit: contain; }
    .funcao button {
      background-color: #2c2c54; color: white;
      border: none; padding: 10px 15px; border-radius: 8px;
      cursor: pointer; font-weight: bold; width: 110px;
    }

    @media (max-width: 768px) {
      .banner { padding: 12px 16px; min-height: 180px; }
      .banner-icon { height: 220px; }
      .banner h2 { font-size: 36px; }
      .banner h3 { font-size: 18px; }
      .botao-cronometro { width: 200px; height: 200px; }
    }
  </style>
</head>
<body>

  <div class="topo">
    <h1>EstudosIA</h1>
    <nav>
      <div class="user-wrapper">
        <a href="editar_usuario.php" style="display: flex; align-items: center; gap: 10px; text-decoration: none;">
          <span><?php echo htmlspecialchars($usuario['nome']); ?></span>
          <img src="<?php echo $usuario['foto'] ? $usuario['foto'] : 'https://i.pinimg.com/236x/ee/c5/cf/eec5cf10cb80af4e4b1c6674445be559.jpg'; ?>" alt="Foto do usuário">
        </a>
        <a href="logout.php" class="logout-link">Sair</a>
      </div>
    </nav>
  </div>

  <div class="banner">
    <img src="/imagens/robolindo-Photoroom-fotor-20250818115256.png" class="banner-icon left" alt="Robozinho lindo esquerdo">
    <div class="banner-text">
      <h2>Inteligência Artificial</h2>
      <h3>Auxiliando nos seus estudos!</h3>
      <button onclick="location.href='resposta.html'">Faça sua pergunta!</button>
    </div>
    <img src="/imagens/robolindo-Photoroom.png" class="banner-icon right" alt="Robozinho lindo direito">
  </div>

  <!-- Cronômetro e quadrados lado a lado -->
  <div class="conteudo-principal">
    <a href="cronometro.php" class="botao-cronometro" title="Abrir cronômetro">
      <img src="/imagens/WhatsApp_Image_2025-08-22_at_09.30.52-removebg-preview.png" alt="Botão Cronômetro">
    </a>

    <div class="funcoes-container">
      <div class="funcao">
        <div class="icone">
          <img src="/imagens/WhatsApp Image 2025-08-18 at 08.52.56.png" alt="Ícone Anotações">
        </div>
        <button onclick="location.href='/anotacoes/index.html'">Anotações</button>
      </div>

      <div class="funcao">
        <div class="icone">
          <img src="/imagens/WhatsApp Image 2025-08-18 at 08.51.12.png" alt="Ícone Arquivos">
        </div>
        <button onclick="location.href='/arquivos/upload_pdf.php'">Arquivos</button>
      </div>

      <div class="funcao">
        <div class="icone">
          <img src="/imagens/WhatsApp Image 2025-08-18 at 08.53.37.png" alt="Ícone Calendário">
        </div>
        <button onclick="location.href='calendario.html'">Calendário</button>
      </div>

      <div class="funcao">
        <div class="icone">
          <img src="/imagens/WhatsApp Image 2025-08-18 at 08.54.17.png" alt="Ícone Planner">
        </div>
        <button onclick="location.href='plano_estudos.html'">Planner</button>
      </div>

      <div class="funcao">
        <div class="icone">
          <img src="/imagens/WhatsApp Image 2025-08-18 at 08.59.18.png" alt="Ícone Flash Card">
        </div>
        <button onclick="location.href='/flashcard.html/index.html'">Flash Card</button>
      </div>
    </div>
  </div>

</body>
</html>