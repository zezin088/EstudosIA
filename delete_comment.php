<?php
include("conexao.php");
session_start();
if(!isset($_SESSION['usuario_id'])) exit;

$commentId = $_POST['commentId'];
$usuario_id = $_SESSION['usuario_id'];

// Só deleta se for dono do comentário
$stmt = $conn->prepare("DELETE FROM comentarios WHERE id=? AND id_usuario=?");
$stmt->bind_param("ii", $commentId, $usuario_id);
$stmt->execute();
$stmt->close();
?>