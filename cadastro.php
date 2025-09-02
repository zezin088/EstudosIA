<?php
// exemplo de conexão
$conn = new mysqli("localhost", "root", "", "bd_usuarios");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // salva no banco (simples, sem hash só p/ teste)
    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";
    if ($conn->query($sql) === TRUE) {
        // grava a mensagem e redireciona para login
        echo "<script>
            localStorage.setItem('mensagemLogin', 'Cadastrado com sucesso!');
            window.location.href = 'login.html';
        </script>";
    } else {
        echo "<script>
            localStorage.setItem('mensagemLogin', 'Erro ao cadastrar');
            window.location.href = 'login.html';
        </script>";
    }
}
?>
