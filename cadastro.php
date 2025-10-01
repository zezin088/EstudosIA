<?php
session_start();
include 'conexao.php'; // sua conexão

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $senha_confirma = $_POST['senha_confirma'];

    // Validação básica
    if ($senha !== $senha_confirma) {
        echo "As senhas não coincidem!";
        exit;
    }

    if (empty($nome) || empty($email) || empty($senha)) {
        echo "Preencha todos os campos!";
        exit;
    }

    // Criptografia da senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Caminho da foto default
    $foto_default = 'imagens/usuarios/default.jpg';

    // Inserção no banco
    $sql = "INSERT INTO usuarios (nome, email, senha, foto) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nome, $email, $senha_hash, $foto_default);

    if ($stmt->execute()) {
        echo "Cadastro realizado com sucesso!";
        // Você pode redirecionar para login:
        // header("Location: login.php");
        // exit;
    } else {
        echo "Erro ao cadastrar: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>

<!-- Formulário HTML simples -->
<form method="POST" action="">
    <input type="text" name="nome" placeholder="Nome" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="senha" placeholder="Senha" required><br>
    <input type="password" name="senha_confirma" placeholder="Confirme a senha" required><br>
    <button type="submit">Cadastrar</button>
</form>
