<?php
session_start();
include('conexao.php');

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.html');
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Excluir a foto (opcional)
$sql_foto = "SELECT foto FROM usuarios WHERE id = ?";
$stmt_foto = $conn->prepare($sql_foto);
$stmt_foto->bind_param("i", $usuario_id);
$stmt_foto->execute();
$stmt_foto->store_result();
$stmt_foto->bind_result($foto);
$stmt_foto->fetch();
$stmt_foto->close();

if (!empty($foto) && file_exists($foto)) {
    unlink($foto);
}

// Excluir o usuário
$sql = "DELETE FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->close();

session_destroy();
header("Location: deletado.html");
exit();
?>
