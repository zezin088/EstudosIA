<?php
session_start();
include 'conexao.php';

if(isset($_SESSION['usuario_id'])){
    $uid = intval($_SESSION['usuario_id']);
    $stmt = $conn->prepare("UPDATE usuarios SET last_online = NOW() WHERE id = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $stmt->close();
}
?>
