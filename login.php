<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <script src="https://accounts.google.com/gsi/client" async defer></script>
  <style>
    @font-face { font-family: raesha; src: url('fonts/Raesha.ttf') format('truetype'); }
    @font-face { font-family: Karst; src: url('fonts/Karst-Light.otf') format('truetype'); }

    * { margin:0; padding:0; box-sizing:border-box; }

    body {
      font-family: Arial, sans-serif;
      background-color: rgb(243, 228, 201);
      color: rgb(192, 98, 98);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      background-color: white;
      border-radius: 15px;
      padding: 40px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    h2 { color: rgb(139,80,80); margin-bottom:15px; font-size:2rem; font-family:'raesha'; }
    p { margin-top:5px; }
    a { text-decoration:none; color: rgb(139,80,80); cursor:pointer; }

    .g_id_signin { width:100%; display:flex; justify-content:center; margin-top:10px; }

    .mensagem {
      padding:10px;
      margin:15px 0;
      border-radius:5px;
      display:block;
      width:100%;
      max-width:400px;
      text-align:center;
      position:fixed;
      top:20px;
      left:50%;
      transform:translateX(-50%);
      z-index:1000;
      opacity:0;
      transition: opacity 0.5s ease-out, top 0.5s ease-out;
      background-color:#d4edda;
      color:#155724;
      border:1px solid #c3e6cb;
    }

    .mensagem.show { opacity:1; top:50px; }

    .input-group {
      position: relative;
      width: 100%;
      margin: 5px 0;
    }

    input {
      width: 100%;
      padding: 12px;
      padding-right: 40px; /* espaço pro botão do olho */
      border: 2px solid rgb(192, 98, 98);
      border-radius: 25px;
      font-size: 1rem;
      color: rgb(139, 80, 80);
      background-color: rgba(243, 228, 201, 0.699);
      outline: none;
      margin: 3px 0;
    }

    input:focus { border-color: rgb(139, 80, 80); }
    input::placeholder { color: rgb(139, 80, 80); opacity: 0.7; }

    /* botão do olho */
    .toggle-password {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      font-size: 1.1rem;
      color: rgb(139, 80, 80);
      background: none;
      border: none;
      padding: 0;
      line-height: 1;
    }
    
    .toggle-password:hover { color: rgb(192, 98, 98); }

    /* botão principal */
    button[type="submit"], .btn-recuperar {
      width:100%;
      padding:12px;
      background-color: rgb(192,98,98);
      color:white;
      border:none;
      border-radius:25px;
      font-size:1.2rem;
      cursor:pointer;
      transition: background-color 0.3s ease;
      margin:10px 0;
    }
    button[type="submit"]:hover, .btn-recuperar:hover { background-color: rgb(139,80,80); }

    /* modal de recuperação */
    .modal {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.5);
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background: #fff;
      padding: 20px;
      border-radius: 15px;
      text-align: center;
      width: 90%;
      max-width: 350px;
    }

    .close {
      float: right;
      font-size: 20px;
      cursor: pointer;
      color: rgb(192,98,98);
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Login</h2>

    <div id="mensagem" class="mensagem"></div>

    <?php
if (isset($_GET['msg'])) {
    echo "<div class='mensagem show'>" . htmlspecialchars($_GET['msg']) . "</div>";
}
?>

    <form action="processa_login.php" method="POST">
      <input type="email" name="email" placeholder="E-mail" required autofocus>

      <div class="input-group">
        <input type="password" id="senha" name="senha" placeholder="Senha" required>
        <button type="button" class="toggle-password" onclick="mostrarSenha()">👁</button>
      </div>

      <button type="submit">Entrar</button>
    </form>

    <p><a id="linkRecuperar">Esqueceu a senha?</a></p>
    <p>Não tem conta? <a href="cadastro.html">Cadastre-se</a></p>

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
  </div>

  <!-- Modal de recuperação -->
  <div id="modalRecuperar" class="modal">
    <div class="modal-content">
      <span class="close" id="fecharModal">&times;</span>
      <h3>Recuperar Senha</h3>
      <p>Digite seu e-mail ou telefone:</p>
      <form action="recuperar.php" method="POST">
        <input type="text" name="contato" placeholder="E-mail ou Telefone" required>
        <button type="submit" class="btn-recuperar">Enviar código</button>
      </form>
    </div>
  </div>

  <script>
    // mensagens
    window.onload = function() {
      const mensagemTexto = localStorage.getItem('mensagemLogin');
      if(mensagemTexto){
        const div = document.getElementById('mensagem');
        div.innerText = mensagemTexto;
        div.classList.add('show');
        setTimeout(() => { div.classList.remove('show'); }, 5000);
        localStorage.removeItem('mensagemLogin');
      }
    }

    function mostrarSenha() {
      const input = document.getElementById("senha");
      input.type = input.type === "password" ? "text" : "password";
    }

    // modal recuperação
    const modal = document.getElementById("modalRecuperar");
    const link = document.getElementById("linkRecuperar");
    const fechar = document.getElementById("fecharModal");

    link.onclick = () => modal.style.display = "flex";
    fechar.onclick = () => modal.style.display = "none";
    window.onclick = (e) => { if (e.target == modal) modal.style.display = "none"; }
  </script>
</body>
</html>
