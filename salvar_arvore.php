<?php
session_start();
include 'conexao.php';
$id = $_SESSION['usuario_id'];
$arvore = $_POST['arvore_escolhida'];

$stmt = $conn->prepare("UPDATE usuarios SET arvore_escolhida = ? WHERE id = ?");
$stmt->bind_param("ii", $arvore, $id);
$stmt->execute();
header("Location: index.html");
?>
