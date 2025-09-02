<?php
include("conexao.php");
session_start();
$usuario_id = $_SESSION['usuario_id'] ?? 1;
$conteudo = $_POST['conteudo'] ?? '';

if($conteudo){
    $stmt = $conn->prepare("INSERT INTO posts (usuario_id, conteudo, data_postagem) VALUES (?, ?, NOW())");
    $stmt->bind_param("is", $usuario_id, $conteudo);
    if($stmt->execute()){
        echo 'ok';
    } else echo 'erro';
    $stmt->close();
}

?>
