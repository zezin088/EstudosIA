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

$nomeUsuario = isset($usuario['nome']) ? $usuario['nome'] : 'Usuário';
$foto_usuario = !empty($usuario['foto']) && file_exists($usuario['foto']) 
    ? $usuario['foto'] 
    : 'imagens/usuarios/default.jpg';
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

    /* ===== Social float & panel (integrated) ===== */
    .social-float {
      position: fixed;
      left: 20px;
      bottom: 20px;
      z-index: 1500;
      display: flex;
      align-items: flex-end;
      gap: 10px;
      pointer-events: none;
    }

    .social-mini {
      pointer-events: auto;
      display: flex;
      align-items: center;
      gap: 10px;
      background: linear-gradient(180deg, #fff, #f7fffb);
      border-radius: 14px;
      padding: 8px 10px;
      box-shadow: 0 12px 30px rgba(0,0,0,0.12);
      border: 1px solid rgba(63,124,114,0.06);
      transition: transform .16s ease, box-shadow .16s ease;
      cursor: pointer;
      user-select: none;
    }
    .social-mini:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(0,0,0,0.16); }

    .social-mini .mini-avatar {
      width: 56px; height:56px; border-radius:10px; overflow:hidden; flex:0 0 56px; display:block;
      border: 2px solid rgba(63,124,114,0.08);
    }
    .social-mini .mini-avatar img { width:100%; height:100%; object-fit:cover; display:block; }

    .social-mini .mini-info { display:flex; flex-direction:column; gap:4px; }
    .social-mini .mini-info .title { font-weight:700; color:#3f7c72; font-size:14px; }
    .social-mini .mini-info .sub { font-size:13px; color:#6b7280; }

    .social-panel-backdrop {
      position: fixed;
      left: 0; top: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.28);
      z-index: 1490;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 18px;
    }

    .social-panel {
      width: min(1100px, 94vw);
      height: min(760px, 86vh);
      border-radius: 14px;
      background: linear-gradient(180deg, #fff, #fbfffe);
      box-shadow: 0 24px 60px rgba(0,0,0,0.26);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      transform: translateY(12px) scale(.98);
      opacity: 0;
      transition: transform .28s cubic-bezier(.2,.9,.28,1), opacity .28s;
    }

    .social-panel.show {
      transform: translateY(0) scale(1);
      opacity: 1;
    }

    .social-panel header {
      display:flex; align-items:center; gap:12px; padding:12px 16px;
      background: linear-gradient(90deg,#3f7c72,#bdebe3);
      color: white; font-weight:700;
    }
    .social-panel header .title { font-size:16px; }
    .social-panel header .spacer { flex:1; }
    .social-panel header button { background: transparent; border: none; color: white; font-weight:700; cursor:pointer; font-size:14px; }

    .social-panel .content {
      flex:1; display:flex; gap:12px; align-items:stretch;
    }
    .social-panel .sidebar {
      width:260px; border-right:1px solid rgba(0,0,0,0.06); padding:12px; background: linear-gradient(180deg,#f8fffb,#fff);
      display:flex; flex-direction:column; gap:10px;
    }
    .social-panel .sidebar .small-card {
      display:flex; gap:10px; align-items:center; background: #fff; padding:8px; border-radius:10px; box-shadow:0 6px 18px rgba(0,0,0,0.06);
    }
    .social-panel .iframe-wrap { flex:1; background: #fff; }
    .social-panel iframe { width:100%; height:100%; border:0; display:block; }

    @media (max-width:900px){
      .social-mini .mini-avatar{ width:48px; height:48px; }
    }
    @media (max-width:600px){
      .social-panel { height: 84vh; }
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

  <!-- ===== New Social Mini + Panel (replaces previous float) ===== -->
  <div class="social-float">
    <div class="social-mini" id="socialMini" role="button" aria-haspopup="dialog" aria-controls="socialPanel" tabindex="0" title="Abrir Rede Social">
      <div class="mini-avatar" aria-hidden="true">
        <img src="<?php echo $foto_usuario; ?>" alt="Preview usuário">
      </div>
      <div class="mini-info">
        <div class="title">Rede Social</div>
        <div class="sub">Ver publicações e interagir</div>
      </div>
    </div>
  </div>

  <div class="social-panel-backdrop" id="socialBackdrop" aria-hidden="true">
    <div class="social-panel" role="dialog" aria-modal="true" aria-label="Rede Social" id="socialPanel">
      <header>
        <div class="title">Rede Social</div>
        <div class="spacer" aria-hidden="true"></div>
        <button id="socialClose" aria-label="Fechar rede social">Fechar ✕</button>
      </header>

      <div class="content">
        <aside class="sidebar" aria-hidden="false">
          <div style="font-weight:700;color:#3f7c72">Sua rede</div>

          <div class="small-card" style="margin-top:8px">
            <div style="width:44px;height:44px;border-radius:8px;overflow:hidden">
              <img src="<?php echo $foto_usuario; ?>" alt="Avatar" style="width:100%;height:100%;object-fit:cover">
            </div>
            <div style="display:flex;flex-direction:column">
              <div style="font-weight:700"><?php echo htmlspecialchars($nomeUsuario); ?></div>
              <div style="font-size:13px;color:#6b7280">Ver perfil</div>
            </div>
          </div>

          <div style="margin-top:12px;font-size:14px;color:#6b7280">Atividades recentes</div>

          <div style="display:flex;flex-direction:column;gap:8px;margin-top:8px">
            <div class="small-card"><div style="width:38px;height:38px;border-radius:8px;overflow:hidden;background:#f3f7f6"></div><div style="margin-left:8px">Nova postagem</div></div>
            <div class="small-card"><div style="width:38px;height:38px;border-radius:8px;overflow:hidden;background:#f3f7f6"></div><div style="margin-left:8px">Comentário</div></div>
          </div>
        </aside>

        <div class="iframe-wrap" style="flex:1;">
          <iframe src="redesocial.php" title="Rede Social" aria-label="Conteúdo da rede social"></iframe>
        </div>
      </div>
    </div>
  </div>

  <!-- Botão flutuante do cronômetro (mantive o CSS acima) -->

  <script>
  // Relógio simples (se quiser reativar, adicione um elemento)
  (function(){
    // placeholder - você já tinha um botão cronômetro flutuante
  })();
  </script>

  <!-- Social JS (open/close, accessibility) -->
  <script>
  (function(){
    const mini = document.getElementById('socialMini');
    const backdrop = document.getElementById('socialBackdrop');
    const panel = document.getElementById('socialPanel');
    const closeBtn = document.getElementById('socialClose');

    function openPanel(){
      backdrop.style.display = 'flex';
      requestAnimationFrame(() => {
        panel.classList.add('show');
        backdrop.setAttribute('aria-hidden', 'false');
      });
      if (closeBtn) closeBtn.focus();
    }

    function closePanel(){
      panel.classList.remove('show');
      backdrop.setAttribute('aria-hidden', 'true');
      setTimeout(() => { backdrop.style.display = 'none'; }, 300);
      if (mini) mini.focus();
    }

    if (mini) {
      mini.addEventListener('click', openPanel);
      mini.addEventListener('keydown', (e) => { if(e.key === 'Enter' || e.key === ' ') { e.preventDefault(); openPanel(); } });
    }

    if (closeBtn) closeBtn.addEventListener('click', closePanel);

    if (backdrop) {
      backdrop.addEventListener('click', (e) => {
        if (e.target === backdrop) closePanel();
      });
      backdrop.style.display = 'none';
      backdrop.style.alignItems = 'center';
      backdrop.style.justifyContent = 'center';
    }

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && backdrop.style.display === 'flex') closePanel();
    });
  })();
  </script>

    </body>
    </html>
