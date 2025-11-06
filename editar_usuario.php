<?php
session_start();
include("conexao.php");

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$sql = "SELECT nome, email, biografia, foto FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($nome, $username, $biografia, $foto, $data_criacao, $favoritos, $tags, $data_nascimento, $escola, $foto_pessoal);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_usuario = $_POST['nome_usuario'] ?? $nome;
    $email = $_POST['email'] ?? $email;
    $senha = !empty($_POST['senha']) ? password_hash($_POST['senha'], PASSWORD_DEFAULT) : null;
    $biografia = $_POST['biografia'] ?? $biografia;

    // Foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
      $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
      $novo_nome = uniqid() . '.' . $ext;
      $destino = 'imagens/usuarios/' . $novo_nome;
      move_uploaded_file($_FILES['foto']['tmp_name'], $destino);
      $foto = $destino;
  } else {
      // MANTÉM A FOTO ANTIGA
      $sql_foto = "SELECT foto FROM usuarios WHERE id = ?";
      $stmt_foto = $conn->prepare($sql_foto);
      $stmt_foto->bind_param("i", $usuario_id);
      $stmt_foto->execute();
      $stmt_foto->bind_result($foto_antiga);
      $stmt_foto->fetch();
      $stmt_foto->close();
      $foto = $foto_antiga;
  }

    if ($senha) {
        $sql = "UPDATE usuarios SET nome = ?, email = ?, senha = ?, biografia = ?, foto = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssi', $nome_usuario, $email, $senha, $biografia, $foto, $usuario_id);
    } else {
        $sql = "UPDATE usuarios SET nome = ?, email = ?, biografia = ?, foto = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssi', $nome_usuario, $email, $biografia, $foto, $usuario_id);
    }

    $stmt->execute();
    $stmt->close();

    header("Location: editar_usuario.php?sucesso=1");
    exit();
}

?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Editar Perfil</title>
  <style>
    @font-face {
      font-family: raesha;
      src: url('fonts/Raesha.ttf') format('truetype');
    }
    @font-face {
      font-family: Karst;
      src: url('fonts/Karst-Light.otf') format('truetype');
    }
    body {
      background-color: rgb(243, 228, 201);
      font-family: 'Karst';
      color: rgb(139, 80, 80);
      padding: 2rem;
      position: relative;
    }
    .container {
      max-width: 500px;
      margin: 3rem auto;
      background: white;
      padding: 2rem;
      border-radius: 20px;
      box-shadow: 0 0 10px #c4a58b;
      position: relative;
      z-index: 1;
    }
    h1 {
      text-align: center;
      color: rgb(139, 80, 80);
      font-family: 'raesha';
    }
    label {
      font-weight: bold;
      display: block;
      margin-top: 1rem;
    }
    input, textarea {
      width: 100%;
      padding: 0.5rem;
      margin-top: 0.3rem;
      border: 1px solid rgb(192, 98, 98);
      border-radius: 10px;
    }
    button {
      background-color: rgb(192, 98, 98);
      color: white;
      border: none;
      padding: 0.6rem 1rem;
      border-radius: 10px;
      cursor: pointer;
      margin-top: 1rem;
    }
    button:hover {
      background-color: rgb(139, 80, 80);
    }
    .foto-preview {
      display: flex;
      justify-content: center;
      margin-top: 1rem;
    }
    .foto-preview img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid rgb(192, 98, 98);
    }
    .botoes {
      display: flex;
      justify-content: space-between;
      margin-top: 2rem;
    }
    .botao-voltar {
      background-color: rgb(243, 228, 201);
      color: rgb(139, 80, 80);
      border: 1px solid rgb(139, 80, 80);
    }
    .alerta {
      position: fixed;
      top: 30px;
      left: 50%;
      transform: translateX(-50%);
      background-color: rgb(192, 98, 98);
      color: white;
      padding: 0.8rem 1.5rem;
      border-radius: 20px;
      font-weight: bold;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
      z-index: 9999;
      animation: fadeOut 4s forwards;
    }
    @keyframes fadeOut {
      0% { opacity: 1; }
      85% { opacity: 1; }
      100% { opacity: 0; transform: translateX(-50%) translateY(-20px); }
    }
  </style>
</head>
<body>
<form id="form-deletar" action="deletar_conta.php" method="POST" style="display:none;"></form>
  <?php if (isset($_GET['sucesso'])): ?>
    <div class="alerta">Alterações salvas com sucesso!</div>
  <?php endif; ?>

  <div class="container">
    <h1>Editar Perfil</h1>
    <form method="POST" enctype="multipart/form-data">
      <label for="foto">Foto de Perfil</label>
      <input type="file" name="foto" id="foto">
<div class="foto-preview">
  <?php 
    $foto_usuario = !empty($foto) && file_exists($foto) 
        ? $foto 
        : 'imagens/usuarios/default.jpg';
  ?>
  <img src="<?php echo $foto_usuario; ?>" alt="Foto do usuário">
</div>
      <label for="nome_usuario">Nome de Usuário</label>
      <input type="text" name="nome_usuario" id="nome_usuario" value="<?php echo htmlspecialchars($nome); ?>" required>

      <label for="email">Email</label>
      <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>

      <label for="senha">Senha</label>
      <input type="password" name="senha" placeholder="Deixe em branco se não for alterar">

      <label for="biografia">Biografia</label>
      <textarea name="biografia" id="biografia" rows="4"><?php echo htmlspecialchars($biografia); ?></textarea>

      <div class="botoes">
  <button type="submit">Salvar Alterações</button>
  
  <button type="button" onclick="confirmarExclusao()" style="background-color: rgb(192, 98, 98); color: white; border: none; padding: 0.6rem 1rem; border-radius: 10px; cursor: pointer;">
    Deletar Conta
  </button>

  <a href="inicio.php">
    <button type="button" class="botao-voltar">Voltar para o Início</button>
  </a>
</div>

    </form>

    <div id="confirmacao" style="display:none; background-color: #fff3f3; padding: 1rem; border-radius: 15px; border: 1px solid #c98b8b; margin-top: 1rem; text-align: center;">
      <p>Tem certeza que deseja deletar sua conta?</p>
      <button onclick="document.getElementById('form-deletar').submit()" style="background-color: rgb(192, 98, 98); color: white; border: none; padding: 0.5rem 1rem; border-radius: 10px;">Sim, quero deletar</button>
      <button onclick="document.getElementById('confirmacao').style.display='none'" style="background-color: rgb(243, 228, 201); color: rgb(139, 80, 80); border: 1px solid rgb(139, 80, 80); padding: 0.5rem 1rem; border-radius: 10px;">Cancelar</button>
    </div>
  </div>

  <script>
  function confirmarExclusao() {
    document.getElementById('confirmacao').style.display = 'block';
  }
  </script>

</body>
</html>