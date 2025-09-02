<?php
include 'conexao.php';
session_start();

header('Content-Type: application/json');

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Usuário não autenticado.']);
    exit;
}

$dados = json_decode(file_get_contents("php://input"), true);

if (!isset($dados['tempo']) || !isset($dados['arvore'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Dados incompletos.']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$tempo = intval($dados['tempo']); // tempo em segundos
$arvore = $dados['arvore'];
$data = date("Y-m-d");

$stmt = $conn->prepare("INSERT INTO registro_estudo (usuario_id, tempo_estudo, data_registro, arvore) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiss", $usuario_id, $tempo, $data, $arvore);

if ($stmt->execute()) {
    echo json_encode(['status' => 'sucesso']);
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => $stmt->error]);
}
?>