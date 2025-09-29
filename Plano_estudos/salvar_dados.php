<?php
session_start();
include 'config.php';

// Usuário simulado
$usuario_id = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $semana = intval($_POST['semana']);
    $atividades = $_POST['atividades'];

    $stmt = $pdo->prepare("INSERT INTO planos (usuario_id, semana, atividades) VALUES (:usuario_id, :semana, :atividades)");
    $stmt->execute([
        ':usuario_id' => $usuario_id,
        ':semana' => $semana,
        ':atividades' => $atividades
    ]);

    echo "Plano da semana $semana salvo com sucesso!";
}
