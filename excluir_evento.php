<?php
session_start();
include 'config.php';

if (!isset($_SESSION['usuario_id'])) {
    die("erro_sessao");
}

$id = $_POST['id'] ?? 0;
$usuario_id = $_SESSION['usuario_id'];

$stmt = $conn->prepare("DELETE FROM eventos WHERE id=? AND usuario_id=?");
$stmt->bind_param("ii", $id, $usuario_id);

if ($stmt->execute()) {
    echo "ok";
} else {
    echo "erro";
}
$stmt->close();
$conn->close();
?>
