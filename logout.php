<?php
session_start();
include("conexao.php");

if (isset($_SESSION['usuario_id'])) {
    $id = $_SESSION['usuario_id'];
    $stmt = $conn->prepare("UPDATE usuarios SET online = 0 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
session_destroy();
header("Location: login.php");
exit();
?>
