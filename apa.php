<?php
session_start();
include 'conexao.php';

// Verifica se o usuário está logado
if (isset($_SESSION['id'])) {
    // Recupera os dados do usuário logado
    $id = $_SESSION['id'];
    $sql = "SELECT * FROM usuarios WHERE id = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        $foto_usuario = $usuario['foto']; // Caminho da foto
    }
} else {
    // Se não estiver logado, exibe uma foto padrão
    $foto_usuario = 'imagens/default-user.png';
}
?>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Estudos IA - Início</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<header class="barra">
    <h1><span class="fb">Estudos IA</span></h1>
    <nav>
        <ul>
            <?php if (isset($usuario)) { ?>
                <!-- Exibe a foto de perfil se o usuário estiver logado -->
                <li><a href="/editar_usuario.php"><img src="<?php echo $foto_usuario; ?>" alt="Foto de Perfil" class="foto-img"></a></li>
                <li></li>
                <li><a href="logout.php">Logout</a></li>
            <?php } else { ?>
                <!-- Exibe o botão de login se o usuário não estiver logado -->
                <button>
                    <a href="login.php">
                        <i class="fa-solid fa-circle-user fa-2xl" style="color: #ffffff;"></i>
                        <span class="j">Login</span>
                    </a>
                </button>
                <li><a href="cadastro.html">Cadastro</a></li>
            <?php } ?>
        </ul>
    </nav>
</header>

  <main class="conteudo">
<?php if (isset($_SESSION['mensagem_login'])): ?>
  <div class="mensagem-sucesso" id="mensagemLogin"><?php echo $_SESSION['mensagem_login']; ?></div>
  <script>
    setTimeout(() => {
      const msg = document.getElementById('mensagemLogin');
      if (msg) {
        msg.style.opacity = '0';
        setTimeout(() => msg.remove(), 500); // Remove depois da transição
      }
    }, 4000); // 4 segundos
  </script>
  <?php unset($_SESSION['mensagem_login']); ?>
<?php endif; ?>

    <section class="chat-ia">
      <div class="botoes">
        <button onclick="location.href='anotacoes.html'">Anotações</button>
        <button onclick="location.href='flashcard.html'">Flashcards</button>
        <button onclick="location.href='plano_estudos.html'">Plano de Estudos</button>
        <button onclick="location.href='arquivos.html'">Arquivos</button>
        <button onclick="location.href='calendario.html'">Calendário</button>
      </div>

      <div class="sugestoes">
        <button id="sugestao-btn">Olá! Bem-vindo(a)!</button>
      </div>
      
      <div class="img-assistente">
        <img src="imagens/3.png" alt="Assistente IA" />
        <button class="fala-botao" onclick="iniciarConversa()">
          Olá! Em que posso te ajudar nos seus estudos hoje?
        </button>
      </div>
    </section>
  </main>

  <script>
    function verificarEnter(e) {
      if (e.key === "Enter") {
        e.preventDefault();
        const inputElem = document.getElementById("perguntaInput");
        const pergunta = inputElem.value.trim();

        if (pergunta) {
          inputElem.value = "";
          const id = Date.now().toString();
          let conversas = JSON.parse(localStorage.getItem("conversas") || "[]");
          conversas.push({ id, texto: pergunta });
          localStorage.setItem("conversas", JSON.stringify(conversas));
          window.location.href = `resposta.html?id=${id}`;
        }
      }
    }

    function enviarParaChat() {
      const pergunta = document.getElementById("perguntaInput").value.trim();
      if (pergunta !== "") {
        localStorage.setItem("perguntaInicial", pergunta);
        window.location.href = "resposta.html";
      }
    }

    function iniciarConversa() {
      const pergunta = "Olá! Em que posso te ajudar nos seus estudos hoje?";
      const chatId = localStorage.getItem("chatAtual") || Date.now().toString();
      localStorage.setItem("chatAtual", chatId);

      const conversas = JSON.parse(localStorage.getItem("conversas")) || {};
      if (!conversas[chatId]) conversas[chatId] = [];

      conversas[chatId].push({ tipo: "usuario", texto: pergunta });
      localStorage.setItem("conversas", JSON.stringify(conversas));
      window.location.href = "resposta.html";
    }
    
  </script>
  <script src="b.js"></script>
</body>
</html>