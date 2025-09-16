<?php
session_start();

// Simulação de usuário logado
$usuario = [
    'nome' => 'Aluno Teste',
    'foto' => null // coloque link de foto real se tiver
];
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Plano de Estudos - Estudos IA</title>
  <style>
    @font-face {
      font-family: 'Raesha';
      src: url('fonts/Raesha.ttf') format('truetype');
    }

    @font-face {
      font-family: 'Karst';
      src: url('fonts/Karst-Light.otf') format('opentype');
    }

    @font-face {
      font-family: 'fontsla';
      src: url('fonts/TheStudentsTeacher-Regular.ttf');
    }

    body {
      margin: 0;
      background-color: #ffffff;
      font-family: 'Karst', sans-serif;
      color: #2c2c54;
      line-height: 1.6;
    }

    .barra {
      background: #4a69bd;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 14px 30px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      color: white;
      font-family: 'Raesha';
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .fb {
      font-family: 'Raesha', cursive;
      font-size: 44px;
      margin: 0;
      color: white;
    }

    nav ul {
      display: flex;
      list-style: none;
      gap: 30px;
      margin: 0;
      padding: 0;
    }

    nav ul a {
      text-decoration: none;
      color: #f1f1f1;
      font-weight: 600;
      font-size: 18px;
      transition: color 0.3s ease, border-bottom 0.3s;
      border-bottom: 2px solid transparent;
    }

    nav ul a:hover {
      color: #cfe0f3;
      border-bottom: 2px solid #cfe0f3;
    }

    /* NOVO NAVBAR SUPERIOR CLEAN */
    .navbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background-color: #ffffff;
      padding: 20px 30px;
      border-bottom: 1px solid #e0e0e0;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
      position: sticky;
      top: 0;
      z-index: 999;
    }

    .btn-voltar {
  background-color: #4a69bd;
  color: #ffffff;
  padding: 10px 14px;
  border-radius: 8px;
  text-decoration: none;
  font-family: 'Karst', sans-serif;
  font-weight: 550;
  transition: background-color 0.3s ease;
  font-size: 15px;
  border: none;
  display: inline-block;
}
.btn-voltar:hover {
  background-color: #3c3c74;
}

    .conteudo {
      background-color: #f1f1f1;
      padding: 50px 30px;
      border-radius: 12px;
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
      max-width: 900px;
      margin: 30px auto;
      color: #2c2c54;
      text-align: center;
    }

    h2 {
      font-family: 'fontsla';
      font-size: 36px;
      margin-bottom: 25px;
      color: #4a69bd;
    }

    .botoes-sugestoes {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      margin-bottom: 35px;
    }

    .botoes-sugestoes button {
      padding: 16px 28px;
      background-color: #3c3c74;
      border: none;
      border-radius: 12px;
      font-size: 20px;
      font-family: 'Karst';
      color: white;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .botoes-sugestoes button:hover {
      background-color: #2c2c54;
      transform: translateY(-2px);
    }

    .item-plano {
      background-color: #9db4cc;
      color: #2c2c54;
      border: none;
      border-radius: 12px;
      font-size: 18px;
      font-family: 'fontsla';
      padding: 16px 22px;
      width: 100%;
      max-width: 450px;
      margin-bottom: 18px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
    }

    .item-plano:focus {
      background-color: #cfe0f3;
      outline: none;
      transform: scale(1.03);
      box-shadow: 0 0 0 4px rgba(0,0,0,0.1);
    }

    .acoes-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 18px;
      margin-top: 25px;
    }

    .botao-acao {
      background-color: #3c3c74;
      color: #f1f1f1;
      font-family: 'Karst';
      font-size: 17px;
      padding: 14px 26px;
      border: none;
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .botao-acao:hover {
      background-color: #2c2c54;
      transform: translateY(-2px);
    }

    #adicionarItemDiv {
      margin-top: 25px;
      display: none;
      gap: 12px;
      align-items: center;
      justify-content: center;
      flex-wrap: wrap;
    }

    #novoItemInput {
      flex: 1;
      max-width: 450px;
      padding: 16px 22px;
      font-size: 18px;
      font-family: 'fontsla';
      border-radius: 12px;
      border: 1px solid #9db4cc;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      color: #2c2c54;
      transition: border-color 0.3s ease;
    }

    #novoItemInput:focus {
      outline: none;
      border-color: #4a69bd;
      box-shadow: 0 0 6px #4a69bd;
    }

    #adicionarItemDiv button {
      background-color: #4a69bd;
      border: none;
      border-radius: 12px;
      padding: 13px 26px;
      font-weight: 600;
      cursor: pointer;
      color: #ffffff;
      font-family: 'Karst';
      font-size: 16px;
      transition: all 0.3s ease;
    }

    #adicionarItemDiv button:hover {
      background-color: #3c3c74;
      transform: translateY(-2px);
    }

    .mensagem-plano {
      font-family: 'Karst';
      font-size: 18px;
      color: #c0392b;
    }

    li {
      font-family: 'Karst';
    }
  </style>
</head>
<body>
  <nav class="topo-nav" role="navigation" aria-label="Navegação principal" style="display:flex; justify-content:space-between; align-items:center; padding:18px 40px; background:#f1f1f1; box-shadow:0 2px 6px rgba(0,0,0,0.1); position:sticky; top:0; z-index:999;">
    <h1 style="font-family: 'Bungee', cursive; font-size:28px; color:#2c2c54; margin:0; user-select:none;">EstudosIA</h1>
    
    <div class="user-menu" style="display:flex; align-items:center; gap:15px;">
      <a href="editar_usuario.php" class="user-info" title="Perfil do usuário <?php echo htmlspecialchars($usuario['nome']); ?>" style="display:flex; align-items:center; gap:12px; text-decoration:none; color:#4a69bd; font-weight:700; font-size:16px; transition:color 0.3s ease;">
        <span><?php echo htmlspecialchars($usuario['nome']); ?></span>
        <img 
    src="<?php echo $usuario['foto'] ? htmlspecialchars($usuario['foto']) : 'https://i.pinimg.com/236x/ee/c5/cf/eec5cf10cb80af4e4b1c6674445be559.jpg'; ?>" 
    alt="Foto do usuário" 
    style="width:42px; height:42px; border-radius:50%; object-fit:cover; border:2px solid #4a69bd;" 
/>

      </a>
      <a href="logout.php" class="logout" title="Sair da conta" style="font-weight:700; color:#c0392b; text-decoration:none; padding:8px 14px; border-radius:8px; border:2px solid transparent; transition:background-color 0.3s ease, color 0.3s ease; font-size:15px; user-select:none;">Sair</a>
    </div>
  </nav>

  
    <a class="btn-voltar" href="/inicio.php" style="margin-left: 30px; margin-right: auto;">⬅️ Voltar</a>
</nav>


  <div class="conteudo" id="conteudo">
    <h2>Plano de Estudos - Selecione uma Semana</h2>
    <div class="botoes-sugestoes">
      <button onclick="mostrarSemana(1)">Semana 1</button>
      <button onclick="mostrarSemana(2)">Semana 2</button>
      <button onclick="mostrarSemana(3)">Semana 3</button>
      <button onclick="mostrarSemana(4)">Semana 4</button>
    </div>

    <div id="conteudo-semanal">
      <p style="font-family: 'Karst'; color: #2c2c54;">Selecione uma semana para ver o plano de estudos.</p>
    </div>

    <div id="adicionarItemDiv">
      <input type="text" id="novoItemInput" placeholder="Digite o novo item aqui..." />
      <button type="button" onclick="confirmarNovoItem()">Adicionar</button>
      <button type="button" onclick="cancelarNovoItem()">Cancelar</button>
    </div>
  </div>

  <script>
    const planos = {
      1: ["📘 Matemática - 1h/dia", "📗 Português - 45min/dia", "✍️ Redação - 3x por semana", "📕 História - 1h/dia", "🔁 Revisão - sábado", "📝 Simulado - domingo"],
      2: ["📘 Física - 1h/dia", "📗 Gramática - 45min/dia", "✍️ Redação - tema novo", "📕 Geografia - 1h/dia", "🔁 Revisão - sábado", "📝 Simulado - domingo"],
      3: ["📘 Química - 1h/dia", "📗 Literatura - 1h/dia", "✍️ Redação - correção", "📕 Sociologia - 1h/dia", "🔁 Revisão - sábado", "📝 Simulado - domingo"],
      4: ["📘 Biologia - 1h/dia", "📗 Português - 1h/dia", "✍️ Redação - 3 textos", "📕 Filosofia - 1h/dia", "🔁 Revisão geral", "📝 Simulado final"]
    };

    let semanaAtual = null;

    function mostrarSemana(semana) {
      semanaAtual = semana;
      const container = document.getElementById("conteudo-semanal");
      container.innerHTML = "";

      planos[semana].forEach(item => {
        const textarea = document.createElement("textarea");
        textarea.value = item;
        textarea.rows = 2;
        textarea.classList.add("item-plano");
        container.appendChild(textarea);
      });

      const botoesContainer = document.createElement("div");
      botoesContainer.classList.add("acoes-container");

      const botaoSalvar = document.createElement("button");
      botaoSalvar.textContent = "Salvar";
      botaoSalvar.classList.add("botao-acao");
      botaoSalvar.type = "button";
      botaoSalvar.onclick = () => {
        const caixas = container.querySelectorAll("textarea");
        const dados = Array.from(caixas).map(caixa => caixa.value);
        alert("Plano salvo:\n" + dados.join("\n"));
      };

      const botaoExcluir = document.createElement("button");
      botaoExcluir.textContent = "Excluir";
      botaoExcluir.classList.add("botao-acao");
      botaoExcluir.type = "button";
      botaoExcluir.onclick = () => {
        container.innerHTML = "";
        const mensagem = document.createElement("p");
        mensagem.classList.add("mensagem-plano");
        mensagem.textContent = "Plano apagado. Selecione novamente uma semana.";
        container.appendChild(mensagem);
      };

      const botaoAdicionar = document.createElement("button");
      botaoAdicionar.textContent = "➕ Adicionar Item";
      botaoAdicionar.classList.add("botao-acao");
      botaoAdicionar.type = "button";
      botaoAdicionar.onclick = mostrarInputAdicionar;

      botoesContainer.appendChild(botaoSalvar);
      botoesContainer.appendChild(botaoExcluir);
      botoesContainer.appendChild(botaoAdicionar);

      container.appendChild(botoesContainer);
      cancelarNovoItem();
    }

    function mostrarInputAdicionar() {
      document.getElementById("adicionarItemDiv").style.display = "flex";
      document.getElementById("novoItemInput").value = "";
      document.getElementById("novoItemInput").focus();
    }

    function confirmarNovoItem() {
      const input = document.getElementById("novoItemInput");
      const novoItem = input.value.trim();
      if (!novoItem) {
        alert("Por favor, digite algum texto.");
        return;
      }
      if (semanaAtual === null) {
        alert("Selecione uma semana antes de adicionar itens.");
        return;
      }
      planos[semanaAtual].push(novoItem);
      mostrarSemana(semanaAtual);
      cancelarNovoItem();
    }

    function cancelarNovoItem() {
      document.getElementById("adicionarItemDiv").style.display = "none";
      document.getElementById("novoItemInput").value = "";
    }
  </script>
</body>
</html>