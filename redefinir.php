<?php
$conn = new mysqli("localhost", "root", "", "bd_usuarios");

if ($conn->connect_error) {
    die("Erro: " . $conn->connect_error);
}

if (isset($_GET["token"])) {
    $token = $_GET["token"];
    $sql = "SELECT * FROM usuarios WHERE token='$token' AND expira_token > NOW()";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        ?>
        <form method="POST" action="">
            <input type="password" name="nova_senha" placeholder="Digite sua nova senha" required>
            <button type="submit">Redefinir</button>
        </form>
        <?php

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nova_senha = password_hash($_POST["nova_senha"], PASSWORD_DEFAULT);
            $conn->query("UPDATE usuarios SET senha='$nova_senha', token=NULL, expira_token=NULL WHERE token='$token'");
            echo "Senha alterada com sucesso!";
        }
    } else {
        echo "Token inválido ou expirado.";
    }
}
?>