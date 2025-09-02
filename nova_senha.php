<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "bd_usuarios";

$conn = new mysqli($host, $user, $pass, $db);
if($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// verifica se o token foi passado
if(isset($_GET['token'])){
    $token = $conn->real_escape_string($_GET['token']);

    // busca usuário com o token
    $result = $conn->query("SELECT id, nome FROM usuarios WHERE token='$token'");
    if($result->num_rows == 1){
        $row = $result->fetch_assoc();
        $usuarioId = $row['id'];
        $nome = $row['nome'];
    } else {
        die("Token inválido ou expirado.");
    }
} else {
    die("Token não fornecido.");
}

// se o formulário foi enviado
if(isset($_POST['nova_senha']) && isset($_POST['confirmar_senha'])){
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    if($nova_senha !== $confirmar_senha){
        $erro = "As senhas não coincidem!";
    } else {
        // criptografa a senha
        $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

        // atualiza senha e remove token
        $conn->query("UPDATE usuarios SET senha='$senha_hash', token=NULL WHERE id='$usuarioId'");

        $sucesso = "Senha atualizada com sucesso! <a href='login.html'>Faça login!</a>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Nova Senha</title>
<style>
body {
    font-family: Arial, sans-serif;
    background-color: rgb(243,228,201);
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}
.container {
    background:white;
    padding:40px;
    border-radius:15px;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
    width:100%;
    max-width:400px;
    text-align:center;
}
input {
    width:100%;
    padding:12px;
    margin:10px 0;
    border:2px solid rgb(192,98,98);
    border-radius:25px;
    outline:none;
}
button {
    width:100%;
    padding:12px;
    background-color: rgb(192,98,98);
    color:white;
    border:none;
    border-radius:25px;
    cursor:pointer;
}
button:hover {
    background-color: rgb(139,80,80);
}
.mensagem {
    color:red;
    margin:10px 0;
}
.sucesso {
    color:green;
    margin:10px 0;
}
</style>
</head>
<body>
<div class="container">
    <h2>Redefinir Senha</h2>

    <?php if(isset($erro)) echo "<div class='mensagem'>$erro</div>"; ?>
    <?php if(isset($sucesso)) echo "<div class='sucesso'>$sucesso</div>"; ?>

    <?php if(!isset($sucesso)) { ?>
    <form method="POST">
        <input type="password" name="nova_senha" placeholder="Nova senha" required>
        <input type="password" name="confirmar_senha" placeholder="Confirmar senha" required>
        <button type="submit">Redefinir Senha</button>
    </form>
    <?php } ?>
</div>
</body>
</html>
