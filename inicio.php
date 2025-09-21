
</html>

<?php
session_start();

// Evitar cache do navegador
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
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

    /* Substituído .topo pelo topo-nav */
    .topo-nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 18px 40px;
      background-color: #f1f1f1;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      position: sticky;
      top: 0;
      z-index: 999;
    }
    .topo-nav h1 {
      font-family: 'Bungee', cursive;
      font-size: 28px;
      color: #2c2c54;
      margin: 0;
      user-select: none;
    }
    .user-menu {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    .user-info {
      display: flex;
      align-items: center;
      gap: 12px;
      text-decoration: none;
      color: #4a69bd;
      font-weight: 700;
      font-size: 16px;
      transition: color 0.3s ease;
    }
    .user-info img {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #4a69bd;
      box-shadow: 0 2px 6px rgba(74,105,189,0.5);
    }
    .logout {
      font-weight: 700;
      color: #c0392b;
      text-decoration: none;
      padding: 8px 14px;
      border-radius: 8px;
      border: 2px solid transparent;
      transition: background-color 0.3s ease, color 0.3s ease;
      font-size: 15px;
      user-select: none;
    }
    .logout:hover {
      background-color: #c0392b;
      color: white;
    }

    /* Banner e restante do CSS mantidos iguais */
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
      flex-direction: row;
      align-items: flex-start;
      gap: 60px;
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
      align-items: center;
      flex-direction: row;
      flex-wrap: wrap;
      gap: 40px;
      max-width: 800px;
      margin: 0 auto;
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

  <!-- Aqui substituí o div topo pela nav topo-nav -->
  <nav class="topo-nav" role="navigation" aria-label="Navegação principal">
    <h1>EstudosIA</h1>

    <div class="user-menu">
      <a href="editar_usuario.php" class="user-info" title="Perfil do usuário <?php echo htmlspecialchars($usuario['nome']); ?>">
        <span><?php echo htmlspecialchars($usuario['nome']); ?></span>
        <?php
$foto_usuario = !empty($usuario['foto']) && file_exists($usuario['foto']) 
    ? $usuario['foto'] 
    : 'imagens/usuarios/default.jpg';
?>
<img src="<?php echo $foto_usuario; ?>" alt="Foto do usuário">

      </a>
      <a href="logout.php" class="logout">Sair</a>
    </div>
  </nav>

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

  <div class="conteudo-principal" style="display: flex; flex-direction: row; align-items: flex-start; gap: 40px; margin: 90px 90px;">
    <div style="flex: 1; min-width: 320px;">
      <!-- Botão flutuante do cronômetro -->
      <a href="cronometro.php" class="botao-cronometro-float" title="Abrir cronômetro">
        <span class="cronometro-float-img"></span>
      </a>
  <style>
    .botao-cronometro-float {
      position: fixed;
      bottom: 32px;
      right: 32px;
      z-index: 2000;
      background: none;
      border: none;
      cursor: pointer;
      box-shadow: 0 4px 16px rgba(44,44,84,0.18);
      border-radius: 50%;
      padding: 0;
      transition: transform 0.2s;
      width: 110px;
      height: 110px;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: visible;
    }
    .botao-cronometro-float:hover {
      transform: scale(1.08);
      box-shadow: 0 8px 24px rgba(44,44,84,0.25);
    }
    .cronometro-float-img {
      width: 110px;
      height: 110px;
      display: block;
      border-radius: 50%;
      background: url('/imagens/relogio.jpg') center center/cover no-repeat;
      box-shadow: 0 2px 8px rgba(44,44,84,0.10);
      clip-path: circle(50% at 50% 50%);
      border: 4px solid #fff;
    }
    @media (max-width: 600px) {
      .botao-cronometro-float {
        bottom: 16px;
        right: 16px;
        width: 70px;
        height: 70px;
      }
      .cronometro-float-img {
        width: 70px;
        height: 70px;
      }
    }
  </style>
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
          <button onclick="location.href='/Plano_estudos/plano_estudos.php'">Planner</button>
        </div>
        <div class="funcao">
          <div class="icone">
            <img src="/imagens/WhatsApp Image 2025-08-18 at 08.59.18.png" alt="Ícone Flash Card">
          </div>
          <button onclick="location.href='/flashcard.html/index.html'">Flash Card</button>
        </div>
      </div>
    </div>

  </div>


  <!-- Botão flutuante da rede social (tipo Messenger) -->
  <div id="social-float-btn" style="position:fixed;bottom:120px;left:32px;width:70px;height:70px;z-index:3200;display:flex;align-items:center;justify-content:center;background:#1a73e8;border-radius:50%;box-shadow:0 4px 16px rgba(44,44,84,0.18);transition:box-shadow 0.2s, top 0.3s, left 0.3s, bottom 0.3s; font-size:38px; color:#fff; font-family:sans-serif;">
  <span style="user-select:none;pointer-events:none;display:flex;align-items:center;justify-content:center;width:100%;height:100%;font-size:32px;font-family:'Arial',sans-serif;">:)</span>
  </div>
  <div id="social-float-window" style="opacity:0;pointer-events:none;position:fixed;z-index:3100;background:#fff;border-radius:18px;box-shadow:0 8px 32px rgba(44,44,84,0.25);overflow:hidden;flex-direction:column;transition:opacity 0.35s cubic-bezier(.4,0,.2,1);width:calc(100vw - 150px);height:calc(100vh - 64px);left:120px;bottom:32px;top:auto;right:32px;max-width:1200px;max-height:900px;">
    <div style="background:#1a73e8;color:#fff;padding:10px 18px;display:flex;align-items:center;">
      <span style="font-weight:bold;">Rede Social</span>
    </div>
    <iframe src="redesocial.php" width="100%" height="100%" style="border:none;flex:1;"></iframe>
  </div>

  <script>
  // Botão flutuante fixo no canto inferior esquerdo
  const socialBtn = document.getElementById('social-float-btn');
  const socialWin = document.getElementById('social-float-window');
  // Variável já declarada abaixo, não repetir
  // Mantém a bolinha fixa no canto inferior esquerdo e abre a janela ao lado
  let chatAberto = false;
  socialBtn.addEventListener('click', function(e) {
    chatAberto = !chatAberto;
    if (chatAberto) {
      socialWin.style.display = 'flex';
      socialWin.style.opacity = '0';
      socialWin.style.pointerEvents = 'none';
      setTimeout(() => {
        socialWin.style.opacity = '1';
        socialWin.style.pointerEvents = 'auto';
      }, 10);
    } else {
      socialWin.style.opacity = '0';
      socialWin.style.pointerEvents = 'none';
      setTimeout(() => {
        socialWin.style.display = 'none';
      }, 350);
    }
  });
  </script>


    </body>
    </html>
