<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT id, nome, senha FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // se você estiver guardando senha em texto puro
        if (password_verify($senha, $user['senha'])) {
            // login OK
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nome'] = $user['nome'];

            echo "<script>
                localStorage.setItem('mensagemLogin', 'Login realizado com sucesso!');
                window.location.href = 'inicio.php';
            </script>";
            exit;
        } else {
            // senha incorreta
            echo "<script>
                localStorage.setItem('mensagemLogin', 'Senha incorreta!');
                window.location.href = 'login.php';
            </script>";
            exit;
        }
    } else {
        // email não encontrado
        echo "<script>
            localStorage.setItem('mensagemLogin', 'E-mail não cadastrado!');
            window.location.href = 'login.php';
        </script>";
        exit;
    }
}
?>