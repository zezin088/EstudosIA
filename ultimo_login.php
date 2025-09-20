<?php
session_start();
include("conexao.php");

if(!isset($_SESSION['usuario_id'])){
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Atualiza último login para indicar que está online
$stmt = $conn->prepare("UPDATE usuarios SET ultimo_login = NOW() WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->close();
?>