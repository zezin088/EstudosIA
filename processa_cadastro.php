<?php
include 'conexao.php';

// Desativa exceptions do MySQLi
mysqli_report(MYSQLI_REPORT_OFF);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome  = trim($_POST['nome']);
    $email = trim(strtolower($_POST['email']));
    $senha = $_POST['senha'];
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $foto = 'imagens/usuarios/default.jpg';

    // Verifica se o e-mail já existe
    $check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result(); // ESSENCIAL para num_rows funcionar

    if ($check->num_rows > 0) {
        echo "<script>
            localStorage.setItem('mensagemLogin', 'Esse e-mail já está cadastrado!');
            window.location.href = 'index.php';
        </script>";
        exit;
    }

    // Prepara e executa INSERT
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, foto) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $email, $senha_hash, $foto);

    if ($stmt->execute()) {
        echo "<script>
            localStorage.setItem('mensagemLogin', 'Cadastrado com sucesso! Agora faça login.');
            window.location.href = 'index.php';
        </script>";
    } else {
        // Qualquer outro erro
        echo "<script>
            localStorage.setItem('mensagemLogin', 'Erro ao cadastrar!');
            window.location.href = 'index.php';
        </script>";
    }

    $stmt->close();
    $check->close();
    $conn->close();
}
?>
