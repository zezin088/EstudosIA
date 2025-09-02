<?php
session_start(); // Inicia a sessão

// Conectar ao banco de dados
include 'conexao.php';

// Verifica se os dados foram enviados via POST
if (isset($_POST['email']) && isset($_POST['senha'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Usar prepared statements para evitar SQL Injection
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);  // 's' indica que o parâmetro é uma string
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verifica se o usuário foi encontrado no banco
    if ($resultado->num_rows > 0) {
        $dados_usuario = $resultado->fetch_assoc();
        
        // Verifica se a senha está correta
        if (password_verify($senha, $dados_usuario['senha'])) {
            // Salva os dados do usuário na sessão
            $_SESSION['usuario'] = $dados_usuario['nome']; // Ou qualquer outra informação relevante
            $_SESSION['usuario_id'] = $dados_usuario['id']; // ID do usuário
            header("Location: inicio.php"); // Redireciona para o inicio.php após login bem-sucedido
            exit();
        } else {
            header("Location: login.html?msg=Email ou senha incorretos");
        }
    } else {
        header("Location: login.html?msg=Email ou senha incorretos");
    }

    // Fecha a conexão
    $stmt->close();
} else {
    echo "Por favor, preencha todos os campos!";
}

$conn->close();
?>