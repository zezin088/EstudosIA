<?php
// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'bd_usuarios');

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Só executa se for método POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Encripta a senha

    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";
    if ($conn->query($sql) === TRUE) {
        echo "Cadastro realizado com sucesso!";
        header("Location: login.html");
        exit();
    } else {
        echo "Erro ao cadastrar: " . $conn->error;
    }
} else {
    // Aqui sim faz sentido o 405
    http_response_code(405);
    echo "Método não permitido.";
}

$conn->close();
?>