<?php
// ===================== CONEXÃO COM O BANCO =====================
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bd_usuarios";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Erro na conexão com o banco: " . $conn->connect_error);
}

// ===================== SALVAR DADOS =====================
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Apagar registros antigos antes de salvar novos
  $conn->query("DELETE FROM financas");

  // Recebe os dados das tabelas (arrays)
  $datas = $_POST["data"] ?? [];
  $descricoes = $_POST["descricao"] ?? [];
  $valores = $_POST["valor"] ?? [];
  $tipos = $_POST["tipo"] ?? [];
  $notas = $_POST["notas"] ?? "";

  // Insere cada linha
  for ($i = 0; $i < count($datas); $i++) {
    $data = $datas[$i];
    $descricao = $descricoes[$i];
    $valor = $valores[$i];
    $tipo = $tipos[$i];

    if (!empty($data) || !empty($descricao) || !empty($valor)) {
      $stmt = $conn->prepare("INSERT INTO financas (data, descricao, valor, tipo) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("ssds", $data, $descricao, $valor, $tipo);
      $stmt->execute();
      $stmt->close();
    }
  }

  // Salvar nota geral
  if (!empty($notas)) {
    $stmt = $conn->prepare("INSERT INTO financas (data, descricao, valor, tipo, notas) VALUES (NULL, NULL, NULL, NULL, ?)");
    $stmt->bind_param("s", $notas);
    $stmt->execute();
    $stmt->close();
  }

  echo "<script>alert('💾 Finanças salvas com sucesso!');</script>";
}

// ===================== CARREGAR DADOS =====================
$financas = [];
$result = $conn->query("SELECT * FROM financas WHERE data IS NOT NULL");
while ($row = $result->fetch_assoc()) {
  $financas[] = $row;
}

$notaSalva = "";
$resNota = $conn->query("SELECT notas FROM financas WHERE notas IS NOT NULL LIMIT 1");
if ($resNota && $resNota->num_rows > 0) {
  $notaSalva = $resNota->fetch_assoc()["notas"];
}

$conn->close();
?><!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Finanças</title>
  <style>
    /* Barra de rolagem personalizada */
    ::-webkit-scrollbar {
      width: 12px;
      height: 12px;
    }

    ::-webkit-scrollbar-track {
      background: #f0f0f0;
      border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
      background: #3f7c72;
      border-radius: 10px;
      border: 3px solid #f0f0f0;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: #2a5c55;
    }

    /* Fonte personalizada */
    @font-face {
      font-family: 'SimpleHandmade';
      src: url(/fonts/SimpleHandmade.ttf);
    }

    * {
      box-sizing: border-box;
    }

    body {
      background-color: #3f7c72ff;
      font-family: 'Roboto', sans-serif;
      text-align: center;
      color: #ffffff;
      padding: 40px;
      margin-top: 100px; /* espaço para o header fixo */
    }

    /* Header fixo */
    header {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 70px;
      background: #ffffffcc;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 2rem;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      z-index: 1000;
    }

    header .logo img {
      height: 450px;
      width: auto;
      display: block;
      margin-left: -85px;
    }

    nav ul {
      list-style: none;
      display: flex;
      align-items: center;
      gap: 20px;
      margin: 0;
      padding: 0;
    }

    nav ul li a {
      text-decoration: none;
      color: black;
      padding: 5px 10px;
      border-radius: 8px;
      transition: .3s;
      font-size: 18px;
    }

    nav ul li a:hover {
      background-color: #bdebe3;
    }

    h1 {
      font-family: 'SimpleHandmade';
      font-size: 50px;
      margin-top: 20px;
    }

    h3 {
      font-family: 'SimpleHandmade';
      font-size: 30px;
    }

    /* Tabela */
    table {
      margin: 20px auto;
      border-collapse: collapse;
      width: 90%;
      background-color: #bdebe3ff;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    th, td {
      font-family: 'SimpleHandmade';
      border: 1px solid #1e3834ff;
      padding: 12px;
      font-size: 25px;
      color: #000;
    }

    th {
      background-color: #1e3834ff;
      color: white;
    }

    /* Área de notas */
    textarea {
      width: 90%;
      height: 80px;
      border-radius: 10px;
      border: 2px solid #2a5c55;
      padding: 10px;
      background-color: #bdebe3ff;
      font-size: 18px;
      font-family: 'SimpleHandmade';
      color: #000;
    }

    /* Botões */
    .btn {
      font-family: 'SimpleHandmade';
      background-color: #2a5c55;
      color: white;
      padding: 10px 20px;
      border-radius: 10px;
      text-decoration: none;
      margin: 10px 5px;
      border: none;
      cursor: pointer;
      font-size: 22px;
      transition: 0.3s;
    }

    .btn:hover {
      background-color: #1e3834ff;
    }

    .btn-excluir {
      background-color: #c0392b;
    }

    .btn-excluir:hover {
      background-color: #a93226;
    }

    /* Inputs */
    input[type="text"],
    input[type="date"],
    select,
    input[type="number"] {
      width: 90%;
      padding: 8px;
      font-size: 14px;
      border-radius: 6px;
      border: 1px solid #2a5c55;
      background-color: #f1f6fb;
      color: #000000;
    }
  </style>
</head>
<body>
  <header>
    <div class="logo"><img src="/imagens/logoatual.png" alt="Logo"></div>
    <nav>
      <ul>
        <li><a href="/anotacoes/index.php">Voltar</a></li>
      </ul>
    </nav>
  </header>

  <h1>Finanças</h1>

  <table id="tabela-financas">
    <thead>
      <tr>
        <th>Data</th>
        <th>Descrição</th>
        <th>Valor (R$)</th>
        <th>Tipo</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><input type="date"></td>
        <td><input type="text" placeholder="Ex: Compra de livro"></td>
        <td><input type="number" step="0.01"></td>
        <td>
          <select>
            <option>Entrada</option>
            <option>Saída</option>
          </select>
        </td>
        <td><button class="btn btn-excluir" onclick="removerLinha(this)">⌫</button></td>
      </tr>
    </tbody>
  </table>

  <button class="btn" onclick="adicionarLinha()">➕ Adicionar</button>
  <button class="btn" onclick="salvarTudo()">💾 Salvar</button>

  <h3>Notas sobre gastos</h3>
  <textarea placeholder="Ex: Gastos do mês, metas de economia..."></textarea>

  <br><br>

  <script>
    function adicionarLinha() {
      const tabela = document.getElementById('tabela-financas').getElementsByTagName('tbody')[0];
      const novaLinha = tabela.insertRow();
      novaLinha.innerHTML = `
        <td><input type="date"></td>
        <td><input type="text" placeholder="Ex: Compra de livro"></td>
        <td><input type="number" step="0.01"></td>
        <td>
          <select>
            <option>Entrada</option>
            <option>Saída</option>
          </select>
        </td>
        <td><button class="btn btn-excluir" onclick="removerLinha(this)">⌫</button></td>
      `;
    }

    function removerLinha(botao) {
      const linha = botao.parentNode.parentNode;
      linha.remove();
    }

    function salvarTudo() {
      const linhas = document.querySelectorAll('#tabela-financas tbody tr');
      const dados = [];

      linhas.forEach(linha => {
        const inputs = linha.querySelectorAll('input, select');
        dados.push({
          data: inputs[0].value,
          descricao: inputs[1].value,
          valor: parseFloat(inputs[2].value),
          tipo: inputs[3].value
        });
      });

      console.log("Dados salvos:", dados);
      alert("Dados salvos no console!");
    }
  </script>

</body>
</html>
