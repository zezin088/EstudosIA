<?php
session_start();
header('Content-Type: application/json');
include 'conexao.php';
if (!isset($_SESSION['usuario_id'])) { echo json_encode(['status'=>'erro','mensagem'=>'Não autenticado']); exit; }
$uid = intval($_SESSION['usuario_id']);
$post_id = intval($_POST['post_id'] ?? 0);
$conteudo = trim($_POST['conteudo'] ?? '');
if(!$post_id || $conteudo === '') { echo json_encode(['status'=>'erro']); exit; }

$stmt = $conn->prepare("INSERT INTO comentarios (id_post, id_usuario, conteudo) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $post_id, $uid, $conteudo);
$stmt->execute();
$stmt->close();

// total atualizado
$stmt2 = $conn->prepare("SELECT COUNT(*) AS total FROM comentarios WHERE id_post = ?");
$stmt2->bind_param("i", $post_id);
$stmt2->execute();
$total = $stmt2->get_result()->fetch_assoc()['total'];
$stmt2->close();

// opcional: notificar autor do post (insere linha em notificacoes)
$stmtp = $conn->prepare("SELECT usuario_id, conteudo FROM posts WHERE id = ?");
$stmtp->bind_param("i",$post_id);
$stmtp->execute();
$pp = $stmtp->get_result()->fetch_assoc();
if($pp && $pp['usuario_id'] != $uid){
    $mensagem = htmlspecialchars($_SESSION['usuario_nome']) . " comentou no seu post";
    $tipo = 'comentario';
    $ref = $post_id;
    $stmtn = $conn->prepare("INSERT INTO notificacoes (usuario_id, mensagem, tipo, referencia_id) VALUES (?, ?, ?, ?)");
    $stmtn->bind_param("issi", $pp['usuario_id'], $mensagem, $tipo, $ref);
    $stmtn->execute();
    $stmtn->close();
}
$stmtp->close();

echo json_encode(['status'=>'sucesso','total'=>intval($total)]);
