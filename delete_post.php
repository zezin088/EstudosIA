<?php
include("conexao.php");
session_start();
if(!isset($_SESSION['usuario_id'])) exit;

$postId = $_POST['postId'];
$usuario_id = $_SESSION['usuario_id'];

// Só deleta se for dono
$stmt = $conn->prepare("DELETE FROM posts WHERE id=? AND usuario_id=?");
$stmt->bind_param("ii", $postId, $usuario_id);
$stmt->execute();
$stmt->close();
?>