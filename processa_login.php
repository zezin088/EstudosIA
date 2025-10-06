<?php
include 'conexao.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    // Busca usuário
    $stmt = $conn->prepare("SELECT id, nome, senha, foto FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($senha, $user['senha'])) {
            // Salva dados na sessão
            $_SESSION['usuario_id']   = $user['id'];
            $_SESSION['usuario_nome'] = $user['nome'];
            $_SESSION['usuario_foto'] = $user['foto'] ?: 'imagens/usuarios/default.jpg';

            // Marca usuário como online
            $stmt2 = $conn->prepare("UPDATE usuarios SET online = 1, ultimo_login = NOW() WHERE id = ?");
            $stmt2->bind_param("i", $user['id']);
            $stmt2->execute();
            $stmt2->close();

            // Mensagem de sucesso
            $_SESSION['mensagemLogin'] = "Login realizado com sucesso!";

            // Redireciona
            header("Location: inicio.php");
            exit;
        } else {
            // Senha incorreta
            $_SESSION['mensagemLogin'] = "Senha incorreta!";
            header("Location: index.php");
            exit;
        }
    } else {
        // E-mail não cadastrado
        $_SESSION['mensagemLogin'] = "E-mail não cadastrado!";
        header("Location: index.php");
        exit;
    }
}
?>
