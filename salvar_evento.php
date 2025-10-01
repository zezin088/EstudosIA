<?php
session_start();
include 'config.php';

if (!isset($_SESSION['usuario_id'])) {
    die("erro_sessao"); // usuário não logado
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST["titulo"] ?? '';
    $data = $_POST["data_evento"] ?? '';
    $hora_inicio = $_POST["hora_inicio"] ?? '';
    $hora_fim = $_POST["hora_fim"] ?? '';
    $usuario_id = $_SESSION['usuario_id'];

    $stmt = $conn->prepare("INSERT INTO eventos (titulo, data_evento, hora_inicio, hora_fim, usuario_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $titulo, $data, $hora_inicio, $hora_fim, $usuario_id);

    if ($stmt->execute()) {
        echo "ok";
    } else {
        echo "erro";
    }
    $stmt->close();
}
$conn->close();
?>
