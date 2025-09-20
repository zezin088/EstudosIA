<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    // Verifica se o e-mail já existe
    $check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>
            localStorage.setItem('mensagemLogin', 'Esse e-mail já está cadastrado!');
            window.location.href = 'login.php';
        </script>";
        exit;
    }

    // Cria hash da senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $foto = 'imagens/usuarios/user.jpg'; // imagem padrão que você baixou
$sql = "INSERT INTO usuarios (nome, email, senha, foto) VALUES (?, ?, ?, ?)";
$sql->bind_param("ssss", $nome, $email, $senha_hash, $foto);

    if ($sql->execute()) {
        echo "<script>
            localStorage.setItem('mensagemLogin', 'Cadastrado com sucesso! Agora faça login.');
            window.location.href = 'login.php';
        </script>";
    } else {
        echo "<script>
            localStorage.setItem('mensagemLogin', 'Erro ao cadastrar!');
            window.location.href = 'cadastro.html';
        </script>";
    }
}
?>
