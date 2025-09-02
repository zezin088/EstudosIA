<?php
include("conexao.php");
session_start();
if(!isset($_SESSION['usuario_id'])) exit;

$postId = $_POST['postId'];
$usuario_id = $_SESSION['usuario_id'];

// Verifica se já curtiu
$stmt = $conn->prepare("SELECT id FROM curtidas WHERE id_post=? AND id_usuario=?");
$stmt->bind_param("ii", $postId, $usuario_id);
$stmt->execute();
$stmt->store_result();

if($stmt->num_rows > 0){
    // Já curtiu, então remove
    $stmt->close();
    $stmt = $conn->prepare("DELETE FROM curtidas WHERE id_post=? AND id_usuario=?");
    $stmt->bind_param("ii", $postId, $usuario_id);
    $stmt->execute();
    $curtiu = false;
}else{
    // Não curtiu ainda, então adiciona
    $stmt->close();
    $stmt = $conn->prepare("INSERT INTO curtidas (id_post, id_usuario) VALUES (?, ?)");
    $stmt->bind_param("ii", $postId, $usuario_id);
    $stmt->execute();
    $curtiu = true;
}
$stmt->close();

// Total de curtidas
$stmt = $conn->prepare("SELECT COUNT(*) FROM curtidas WHERE id_post=?");
$stmt->bind_param("i", $postId);
$stmt->execute();
$stmt->bind_result($total);
$stmt->fetch();
$stmt->close();

// Retorna JSON
echo json_encode(['curtiu'=>$curtiu, 'total'=>$total]);
?>