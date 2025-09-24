<?php
session_start();
include 'config.php';

// Simulação de usuário logado
$usuario_id = 1;
$usuario = [
    'nome' => 'Aluno Teste',
    'foto' => 'avatar_padrao.png'
];

// Recebe POST JSON do JS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    $data = json_decode(file_get_contents('php://input'), true);

    // --- EXCLUIR ITEM INDIVIDUAL ---
    if (isset($data['excluir_item'], $data['semana'], $data['indice'])) {
        $semana = intval($data['semana']);
        $indice = intval($data['indice']);

        // Busca o plano atual
        $res = $conn->prepare("SELECT id, conteudo FROM plano_estudos WHERE usuario_id=? AND semana=?");
        $res->bind_param("ii", $usuario_id, $semana);
        $res->execute();
        $result = $res->get_result();
        
        if ($result->num_rows === 0) {
            echo "Nenhum plano encontrado para excluir item.";
            exit;
        }

        $row = $result->fetch_assoc();
        $itens = explode("\n", $row['conteudo']);

        if (!isset($itens[$indice])) {
            echo "Item não encontrado.";
            exit;
        }

        array_splice($itens, $indice, 1); // remove item
        $novo_conteudo = implode("\n", $itens);

        $upd = $conn->prepare("UPDATE plano_estudos SET conteudo=? WHERE id=?");
        $upd->bind_param("si", $novo_conteudo, $row['id']);
        
        if ($upd->execute()) {
            echo "Item excluído com sucesso!";
        } else {
            echo "Erro ao atualizar plano: " . $upd->error;
        }
        exit;
    }

    // --- EXCLUIR TODA A SEMANA ---
    if (isset($data['excluir_tudo'], $data['semana']) && $data['excluir_tudo'] === true) {
        $semana = intval($data['semana']);
        $sql = $conn->prepare("DELETE FROM plano_estudos WHERE usuario_id=? AND semana=?");
        $sql->bind_param("ii", $usuario_id, $semana);
        
        if ($sql->execute()) {
            echo "Plano da semana $semana excluído com sucesso!";
        } else {
            echo "Erro ao excluir plano: " . $sql->error;
        }
        exit;
    }

    // --- SALVAR PLANO ---
    if (isset($data['semana'], $data['itens'])) {
        $semana = intval($data['semana']);
        $conteudo = implode("\n", $data['itens']);

        $check = $conn->prepare("SELECT id FROM plano_estudos WHERE usuario_id=? AND semana=?");
        $check->bind_param("ii", $usuario_id, $semana);
        $check->execute();
        $resCheck = $check->get_result();

        if ($resCheck->num_rows > 0) {
            $row = $resCheck->fetch_assoc();
            $sql = $conn->prepare("UPDATE plano_estudos SET conteudo=? WHERE id=?");
            $sql->bind_param("si", $conteudo, $row['id']);
        } else {
            $sql = $conn->prepare("INSERT INTO plano_estudos (usuario_id, semana, conteudo) VALUES (?,?,?)");
            $sql->bind_param("iis", $usuario_id, $semana, $conteudo);
        }

        if ($sql->execute()) {
            echo "Plano da semana $semana salvo com sucesso!";
        } else {
            echo "Erro ao salvar plano: " . $sql->error;
        }
        exit;
    }
}

// Carrega planos existentes do usuário
$planos_usuario = [];
$result = $conn->query("SELECT * FROM plano_estudos WHERE usuario_id=$usuario_id");
while ($row = $result->fetch_assoc()) {
    $planos_usuario[$row['semana']] = explode("\n", $row['conteudo']);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Plano de Estudos - Estudos IA</title>
  <style>
    @font-face { font-family: 'Raesha'; src: url('fonts/Raesha.ttf') format('truetype'); } @font-face { font-family: 'Karst'; src: url('fonts/Karst-Light.otf') format('opentype'); } @font-face { font-family: 'fontsla'; src: url('fonts/TheStudentsTeacher-Regular.ttf'); } body { margin: 0; background-color: #ffffff; font-family: 'Karst', sans-serif; color: #2c2c54; line-height: 1.6; } .barra { background: #4a69bd; display: flex; justify-content: space-between; align-items: center; padding: 14px 30px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); color: white; font-family: 'Raesha'; position: sticky; top: 0; z-index: 100; } .fb { font-family: 'Raesha', cursive; font-size: 44px; margin: 0; color: white; } nav ul { display: flex; list-style: none; gap: 30px; margin: 0; padding: 0; } nav ul a { text-decoration: none; color: #f1f1f1; font-weight: 600; font-size: 18px; transition: color 0.3s ease, border-bottom 0.3s; border-bottom: 2px solid transparent; } nav ul a:hover { color: #cfe0f3; border-bottom: 2px solid #cfe0f3; } /* NOVO NAVBAR SUPERIOR CLEAN */ .navbar { display: flex; align-items: center; justify-content: space-between; background-color: #ffffff; padding: 20px 30px; border-bottom: 1px solid #e0e0e0; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05); position: sticky; top: 0; z-index: 999; } .btn-voltar { background-color: #4a69bd; color: #ffffff; padding: 10px 14px; border-radius: 8px; text-decoration: none; font-family: 'Karst', sans-serif; font-weight: 550; transition: background-color 0.3s ease; font-size: 15px; border: none; display: inline-block; } .btn-voltar:hover { background-color: #3c3c74; } .conteudo { background-color: #f1f1f1; padding: 50px 30px; border-radius: 12px; box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1); max-width: 900px; margin: 30px auto; color: #2c2c54; text-align: center; } h2 { font-family: 'fontsla'; font-size: 36px; margin-bottom: 25px; color: #4a69bd; } .botoes-sugestoes { display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; margin-bottom: 35px; } .botoes-sugestoes button { padding: 16px 28px; background-color: #3c3c74; border: none; border-radius: 12px; font-size: 20px; font-family: 'Karst'; color: white; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 10px rgba(0,0,0,0.1); } .botoes-sugestoes button:hover { background-color: #2c2c54; transform: translateY(-2px); } .item-plano { background-color: #9db4cc; color: #2c2c54; border: none; border-radius: 12px; font-size: 18px; font-family: 'fontsla'; padding: 16px 22px; width: 100%; max-width: 450px; margin-bottom: 18px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); transition: all 0.3s ease; } .item-plano:focus { background-color: #cfe0f3; outline: none; transform: scale(1.03); box-shadow: 0 0 0 4px rgba(0,0,0,0.1); } .acoes-container { display: flex; flex-wrap: wrap; justify-content: center; gap: 18px; margin-top: 25px; } .botao-acao { background-color: #3c3c74; color: #f1f1f1; font-family: 'Karst'; font-size: 17px; padding: 14px 26px; border: none; border-radius: 12px; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 10px rgba(0,0,0,0.1); } .botao-acao:hover { background-color: #2c2c54; transform: translateY(-2px); } #adicionarItemDiv { margin-top: 25px; display: none; gap: 12px; align-items: center; justify-content: center; flex-wrap: wrap; } #novoItemInput { flex: 1; max-width: 450px; padding: 16px 22px; font-size: 18px; font-family: 'fontsla'; border-radius: 12px; border: 1px solid #9db4cc; box-shadow: 0 4px 10px rgba(0,0,0,0.1); color: #2c2c54; transition: border-color 0.3s ease; } #novoItemInput:focus { outline: none; border-color: #4a69bd; box-shadow: 0 0 6px #4a69bd; } #adicionarItemDiv button { background-color: #4a69bd; border: none; border-radius: 12px; padding: 13px 26px; font-weight: 600; cursor: pointer; color: #ffffff; font-family: 'Karst'; font-size: 16px; transition: all 0.3s ease; } #adicionarItemDiv button:hover { background-color: #3c3c74; transform: translateY(-2px); } .mensagem-plano { font-family: 'Karst'; font-size: 18px; color: #c0392b; } li { font-family: 'Karst'; }
  </style>
</head>
<body>
  <nav>
    <h1>EstudosIA</h1>
    <div class="user-info">
      <a href="editar_usuario.php"><?php echo htmlspecialchars($usuario['nome']); ?></a>
      <img src="<?php echo htmlspecialchars($usuario['foto']); ?>" alt="Foto do usuário" />
      <a href="logout.php" style="color: #c0392b;">Sair</a>
    </div>
  </nav>

  <a class="btn-voltar" href="/inicio.php">⬅️ Voltar</a>

  <div class="conteudo" id="conteudo">
    <h2>Plano de Estudos - Selecione uma Semana</h2>
    <div class="botoes-sugestoes">
      <button onclick="mostrarSemana(1)">Semana 1</button>
      <button onclick="mostrarSemana(2)">Semana 2</button>
      <button onclick="mostrarSemana(3)">Semana 3</button>
      <button onclick="mostrarSemana(4)">Semana 4</button>
    </div>

    <div id="conteudo-semanal">
      <p>Selecione uma semana para ver o plano de estudos.</p>
    </div>

    <div id="adicionarItemDiv">
      <input type="text" id="novoItemInput" placeholder="Digite o novo item aqui..." />
      <button type="button" onclick="confirmarNovoItem()">Adicionar</button>
      <button type="button" onclick="cancelarNovoItem()">Cancelar</button>
    </div>
  </div>

  <script>
    const planos = {
      1: ["📘 Matemática - 1h/dia", "📗 Português - 45min/dia", "✍️ Redação - 3x por semana"],
      2: ["📘 Física - 1h/dia", "📗 Gramática - 45min/dia", "✍️ Redação - tema novo"],
      3: ["📘 Química - 1h/dia", "📗 Literatura - 1h/dia", "✍️ Redação - correção"],
      4: ["📘 Biologia - 1h/dia", "📗 Português - 1h/dia", "✍️ Redação - 3 textos"]
    };
    let semanaAtual = null;

    function mostrarSemana(semana) {
      semanaAtual = semana;
      const container = document.getElementById("conteudo-semanal");
      container.innerHTML = "";

      planos[semana].forEach((item, index) => {
        const textarea = document.createElement("textarea");
        textarea.value = item;
        textarea.rows = 2;
        textarea.classList.add("item-plano");
        textarea.setAttribute('data-indice', index); // adicionar índice
        container.appendChild(textarea);
      });

      // Botões de ação
      const divBotoes = document.createElement('div');
      divBotoes.className = 'acoes-container';

      const salvar = document.createElement('button');
      salvar.textContent = 'Salvar';
      salvar.className = 'botao-acao';
      salvar.onclick = salvarPlano;

      const excluir = document.createElement('button');
      excluir.textContent = 'Excluir Todos';
      excluir.className = 'botao-acao';
      excluir.onclick = () => {
        if (confirm('Deseja realmente excluir todos os itens da semana?')) {
          fetch('plano_estudos.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ excluir_tudo: true, semana: semanaAtual })
          }).then(res => res.text()).then(alert).catch(console.error);
          container.innerHTML = ''; // Limpar o container
        }
      };

      const adicionar = document.createElement('button');
      adicionar.textContent = '➕ Adicionar Item';
      adicionar.className = 'botao-acao';
      adicionar.onclick = () => {
        document.getElementById('adicionarItemDiv').style.display = 'flex';
        document.getElementById('novoItemInput').value = '';
        document.getElementById('novoItemInput').focus();
      };

      divBotoes.appendChild(salvar);
      divBotoes.appendChild(excluir);
      divBotoes.appendChild(adicionar);
      container.appendChild(divBotoes);
    }

    function confirmarNovoItem() {
      const input = document.getElementById('novoItemInput');
      if (!input.value.trim()) return alert('Digite algum texto.');
      const container = document.getElementById('conteudo-semanal');
      const ta = document.createElement('textarea');
      ta.value = input.value.trim();
      ta.className = 'item-plano';
      container.insertBefore(ta, container.querySelector('.acoes-container')); // Inserir antes dos botões
      document.getElementById('adicionarItemDiv').style.display = 'none';
    }

    function cancelarNovoItem() {
      document.getElementById('adicionarItemDiv').style.display = 'none';
    }

    function salvarPlano() {
      if (!semanaAtual) return alert('Selecione uma semana.');
      const container = document.getElementById('conteudo-semanal');
      const textareas = container.querySelectorAll('textarea.item-plano');
      const itens = Array.from(textareas).map(t => t.value);

      fetch('plano_estudos.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ semana: semanaAtual, itens: itens })
      })
      .then(res => res.text())
      .then(msg => {
        alert(msg);
        planos[semanaAtual] = itens; // Atualiza o JS
      })
      .catch(err => alert('Erro ao salvar plano: ' + err));
    }
  </script>
</body>
</html>