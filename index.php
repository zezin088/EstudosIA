<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Estudos IA - Boas-vindas</title>
  <!-- Fontes -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Poppins:wght@300;400;600&family=Luckiest+Guy&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <meta name="viewport" content="width=device-width,initial-scale=1">
<style>
:root{
  --accent: #87ceeb;
  --muted: #91e7e0;
}

/* BODY E HEADER */
html, body { height: 100%; margin: 0; padding: 0; }
body {
  min-height: 100vh;
  background: #000;
  font-family: 'Poppins', Arial, sans-serif;
  overflow-x: hidden;
  color: #fff;
}

header {
  width: 100vw; height: 70px;
  background: rgba(0,0,0,0.85);
  position: fixed; top: 0; left: 0; z-index: 100;
  display: flex; align-items: center; justify-content: space-between;
  padding: 0 48px 0 40px;
}
.logo-site {
  font-family: 'Luckiest Guy', cursive;
  font-size: 2.1rem; color: var(--accent);
  letter-spacing: 2px;
  text-shadow: 1px 1px 0 #fff, 0 2px 8px #0002;
  user-select: none;
}
.nav-btns { display: flex; gap: 12px; }
.nav-btns a {
  text-decoration: none;
  color: var(--muted);
  padding: 6px 16px; border-radius: 18px;
  font-size: 0.9rem; font-weight: 600;
  display: flex; align-items: center; gap: 8px;
  transition: transform .2s, background .25s;
}
.nav-btns a:hover{
  background:#fff; color:#222;
  transform: scale(1.05) rotate(-2deg);
}
.header-space { height: 70px; }

/* QUADRADO LATERAL VERTICAL, LADO ESQUERDO */
.circulo-lateral {
  position: absolute;
  left: 12vw;                  
  top: 20%;                   
  transform: translateY(-50%);
  width: 400px;               
  padding: 30px 20px 15px 20px; 
  background: rgba(0,0,0,0);    /* transparente */
  border: 2px solid #fff;       /* borda branca */
  backdrop-filter: blur(14px);       
  border-radius: 24px;
  box-shadow: 0 8px 32px rgba(0,0,0,0.25);
  display: flex;
  justify-content: center;       
  align-items: flex-start;       
  z-index: 2;
  max-height: calc(100vh - 320px);
  box-sizing: border-box;
  transition: background 0.6s ease;
}

/* Modo cadastro (fundo azul levemente transparente) */
.circulo-lateral.cadastro-mode {
  background: rgba(145,231,224,0.2); 
}

/* FORMULARIO VERTICAL CENTRALIZADO */
.circulo-form-container {
  display: block;        
  width: 100%;
  text-align: center;
  margin: 0 auto;
  padding: 0;
}

.circulo-form {
  display: block;           
  width: 100%;
  margin: 0 auto;
  padding: 0;
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.6s ease, transform 0.6s ease;
}
.circulo-form.active {
  opacity: 1;
  pointer-events: auto;
}

/* INPUTS E BOTÕES */
.circulo-form input {
  display: block;
  width: 90%;
  margin: 8px auto;
  padding: 12px 16px;
  font-size: 1rem;
  border-radius: 8px;
  border: 1px solid #fff;
  background-color: rgba(255,255,255,0.05);
  color: #fff;
  text-align: center;
  box-sizing: border-box;
}
.circulo-form button {
  display: block;
  width: 60%;
  margin: 12px auto;
  padding: 10px 20px;
  font-size: 1rem;
  border-radius: 8px;
  border: 2px solid #fff;
  background-color: rgba(0,0,0,0);
  color: #fff;
  cursor: pointer;
  transition: background 0.3s, color 0.3s;
}
.circulo-form button:hover {
  background-color: #fff;
  color: #000;
}

/* INPUT PASSWORD COM BOTÃO 👁 */
.input-group {
  position: relative;
  display: block;
  margin: 8px auto;
  width: 90%;
}
.input-group input {
  width: 100%;
  padding-right: 40px;
  box-sizing: border-box;
}
.input-group .toggle-password {
  position: absolute;
  right: 8px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1rem;
}

/* TOGGLE CADASTRE-SE */
.toggle-form {
  font-size: 0.9rem;
  color: #fff;
  display: block;
  margin: 10px auto 0 auto;
  text-align: center;
}
.toggle-form .switch {
  color: var(--accent);
  font-weight: 600;
  cursor: pointer;
  text-decoration: underline;
}

/* MENSAGEM DO ROBO */
.mensagem-robo {
  position: absolute;
  left: 68vw; top: 8vw;
  transform: translate(-50%, 0);
  width: 40vw; max-width: 600px;
  text-align: center;
  font-family: 'Luckiest Guy', cursive;
  font-size: 3.2rem; color: #fff;
  letter-spacing: 2px;
  text-shadow: 2px 2px 0 var(--muted), 0 2px 16px #0005;
  z-index: 10; pointer-events: none; user-select: none;
}

/* VÍDEO DE FUNDO */
.robo-bg-video {
  position: absolute; right: 1vw; top: 30px;
  width: 700px; height: 700px;
  max-width: 80vw; max-height: 80vw;
  min-width: 220px; min-height: 220px;
  z-index: 1; pointer-events: none;
  filter: brightness(0.95) contrast(1.1) saturate(1.1);
  border-radius: 50%; overflow: hidden;
  box-shadow: 0 0 80px 0 #000a, 0 0 0 8px #000;
  background: #000; display:flex; align-items:center; justify-content:center;
  clip-path: circle(41% at 50% 50%);
}
.robo-bg-video video{
  width:100%; height:100%; object-fit:cover;
  border-radius:50%; display:block;
}

/* QUADRADOS */
.quadrados-section {
  margin: 0 auto;
  display:flex; flex-direction:row; justify-content:center; gap:40px;
  width:100vw; min-height:60vh;
  padding-top:30px; padding-bottom:80px;
  position:relative; z-index:2;
}
.quadrado{
  width:320px; height:320px;
  background:linear-gradient(135deg,#232323 60%,#444 100%);
  border-radius:32px; box-shadow:0 6px 32px #000a;
  display:flex; align-items:center; justify-content:center;
  color:#fff; font-size:1.5rem;
  font-family:'Poppins', sans-serif;
  transition:transform .2s, box-shadow .2s;
  cursor:pointer;
}
.quadrado:hover{
  transform:scale(1.04) translateY(-8px);
  box-shadow:0 12px 40px #ffb30055;
}

/* RESPONSIVO */
@media (max-width:1100px){
  .quadrados-section{ flex-direction:column; align-items:center; gap:32px; }
  .quadrado{ width:80vw; height:220px; font-size:1.1rem; }
}
@media (max-width:900px){
  .circulo-lateral{ left:50%; transform:translateX(-50%); width:90vw; padding:30px 20px 15px 20px; }
  .robo-bg-video{ right:1vw; top:18vw; width:80vw; height:80vw; }
  .mensagem-robo{ left:70vw; top:16vw; width:60vw; font-size:2rem; }
}
@media (max-width:600px){
  .circulo-lateral{ width:92vw; padding:20px 15px 10px 15px; left:50%; transform:translateX(-50%); }
  .mensagem-robo{ left:70vw; top:28vw; width:80vw; font-size:1.3rem; }
  .robo-bg-video{ right:0; top:90vw; width:92vw; height:92vw; }
  .quadrado{ width:92vw; height:120px; }
}

.g_id_signin { width:100%; display:flex; justify-content:center; margin-top:10px; }

</style>


</head>
<body>
  <header>
    <span class="logo-site">Estudos IA</span>
    <div class="nav-btns" style="margin: 0 60px 0 auto;">
      <a href="sobre_nos.html">Sobre Nós</a>
    </div>
  </header>

  <div class="header-space"></div>

  <div class="mensagem-robo">Olá, seja bem-vindo(a) ao Estudos IA</div>

  <div style="position:relative; min-height:calc(100vw + 48px);">
<div class="circulo-lateral">
  <div class="circulo-form-container">
<!-- LOGIN no círculo -->
<form class="circulo-form login active" action="processa_login.php" method="POST">
  <h2>Login</h2>

  <!-- Mensagens -->
  <div id="mensagem" class="mensagem"></div>
  <?php
  if (isset($_GET['msg'])) {
      echo "<div class='mensagem show'>" . htmlspecialchars($_GET['msg']) . "</div>";
  }
  ?>

  <!-- Campos -->
  <input type="email" name="email" placeholder="E-mail" required autofocus>

  <div class="input-group">
    <input type="password" id="senha" name="senha" placeholder="Senha" required>
    <button type="button" class="toggle-password" onclick="mostrarSenha()">👁</button>
  </div>

  <button type="submit">Entrar</button>

  <p><a id="linkRecuperar">Esqueceu a senha?</a></p>
  <p class="toggle-form">Não tem uma conta? <span class="switch">Cadastre-se</span></p>

  <!-- Login com Google -->
  <div id="g_id_onload"
       data-client_id="912161681251-ak3vkdll5oknq0ssd0uv44ikpvq59q27.apps.googleusercontent.com"
       data-auto_prompt="false">
  </div>

  <div class="g_id_signin"
       data-type="standard"
       data-shape="pill"
       data-theme="filled_blue"
       data-text="signin_with"
       data-size="large"></div>
</form>

<!-- CADASTRO no círculo -->
<form class="circulo-form cadastro" id="cadastroForm" action="processa_cadastro.php" method="POST">
  <h2>Cadastro</h2>

  <!-- Campos -->
  <input type="text" name="nome" placeholder="Nome completo" required>
  <input type="email" name="email" placeholder="E-mail" required>

  <div class="input-group">
    <input type="password" id="senhaCadastro" name="senha" placeholder="Senha" required>
    <button type="button" class="toggle-password" onclick="mostrarSenhaCadastro()">👁</button>
  </div>

  <button type="submit">Cadastrar</button>

  <!-- Login com Google -->
  <div class="google-btn">
    <div id="g_id_onload_cadastro"
      data-client_id="912161681251-ak3vkdll5oknq0ssd0uv44ikpvq59q27.apps.googleusercontent.com"
      data-login_uri="#"
      data-auto_prompt="false">
    </div>

    <div class="g_id_signin"
      data-type="standard"
      data-shape="outline"
      data-theme="outline"
      data-text="signup_with"
      data-size="large"
      data-logo_alignment="left">
    </div>
  </div>

  <p class="toggle-form">Já tem uma conta? <span class="switch">Login</span></p>
</form>

<script>
function mostrarSenhaCadastro() {
  const input = document.getElementById("senhaCadastro");
  input.type = input.type === "password" ? "text" : "password";
}

// Limpa mensagens antigas de login/cadastro
localStorage.removeItem('mensagemLogin');
</script>
  </div>
</div>

    <div class="robo-bg-video">
      <video src="videos/robol.mp4" autoplay loop muted playsinline></video>
    </div>

<script>
const circulo = document.querySelector('.circulo-lateral');
const loginForm = document.querySelector('.circulo-form.login');
const cadastroForm = document.querySelector('.circulo-form.cadastro');
const switches = document.querySelectorAll('.switch');

switches.forEach(btn => {
  btn.addEventListener('click', () => {
    let fromForm, toForm, modeClass;

    if(loginForm.classList.contains('active')) {
      fromForm = loginForm;
      toForm = cadastroForm;
      modeClass = 'cadastro-mode';
    } else {
      fromForm = cadastroForm;
      toForm = loginForm;
      modeClass = null;
    }

    // animação de saída
    fromForm.classList.remove('active');

    // espera a animação de saída antes de mostrar o novo formulário
    setTimeout(() => {
      toForm.classList.add('active');
      if(modeClass) {
        circulo.classList.add(modeClass);
      } else {
        circulo.classList.remove('cadastro-mode');
      }
    }, 300); // metade do tempo de transição CSS
  });
});
function mostrarSenha() {
      const input = document.getElementById("senha");
      input.type = input.type === "password" ? "text" : "password";
    }
</script>
</body>
</html>
