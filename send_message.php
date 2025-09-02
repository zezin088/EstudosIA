<?php
session_start();
include("conexao.php");

if(!isset($_SESSION['usuario_id'])) exit;
$usuario_id = $_SESSION['usuario_id'];
$friendId = $_POST['friendId'];
$msg = $_POST['message'];

$stmt = $conn->prepare("INSERT INTO mensagens (id_remetente,id_destinatario,conteudo) VALUES (?,?,?)");
$stmt->bind_param("iis", $usuario_id, $friendId, $msg);
$stmt->execute();
$stmt->close();

echo json_encode(['success'=>true]);
?>