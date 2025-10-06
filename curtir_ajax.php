<?php
session_start();
header('Content-Type: application/json');
include 'conexao.php';
if (!isset($_SESSION['usuario_id'])) { echo json_encode(['status'=>'erro','mensagem'=>'Não autenticado']); exit; }
$uid = intval($_SESSION['usuario_id']);
$post_id = intval($_GET['post_id'] ?? 0);
if(!$post_id) { echo json_encode(['status'=>'erro']); exit; }

// verifica se já curtiu
$stmt = $conn->prepare("SELECT id FROM curtidas WHERE id_post = ? AND id_usuario = ?");
$stmt->bind_param("ii", $post_id, $uid);
$stmt->execute();
$res = $stmt->get_result();
if($res->num_rows){
    // remover (descurtir)
    $stmt2 = $conn->prepare("DELETE FROM curtidas WHERE id_post = ? AND id_usuario = ?");
    $stmt2->bind_param("ii", $post_id, $uid);
    $stmt2->execute();
    $stmt2->close();
    $ja_curti = 0;
} else {
    // inserir
    $stmt2 = $conn->prepare("INSERT INTO curtidas (id_post, id_usuario) VALUES (?, ?)");
    $stmt2->bind_param("ii", $post_id, $uid);
    $stmt2->execute();
    $stmt2->close();
    $ja_curti = 1;
}
$stmt->close();

// total atualizado
$stmt3 = $conn->prepare("SELECT COUNT(*) AS total FROM curtidas WHERE id_post = ?");
$stmt3->bind_param("i", $post_id);
$stmt3->execute();
$res3 = $stmt3->get_result()->fetch_assoc();
$total = intval($res3['total']);
$stmt3->close();

echo json_encode(['status'=>'sucesso','total'=>$total,'ja_curti'=>$ja_curti]);
