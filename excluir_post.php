<?php
session_start();
header('Content-Type: application/json');
include 'conexao.php';
if (!isset($_SESSION['usuario_id'])) { echo json_encode(['status'=>'erro']); exit; }
$uid = intval($_SESSION['usuario_id']);
$post_id = intval($_POST['post_id'] ?? 0);
if(!$post_id){ echo json_encode(['status'=>'erro']); exit; }

// confirma dono
$stmt = $conn->prepare("SELECT usuario_id, imagem FROM posts WHERE id = ?");
$stmt->bind_param("i",$post_id);
$stmt->execute();
$res = $stmt->get_result();
if(!$row = $res->fetch_assoc()){ echo json_encode(['status'=>'erro','mensagem'=>'Post não encontrado']); exit; }
if($row['usuario_id'] != $uid){ echo json_encode(['status'=>'erro','mensagem'=>'Sem permissão']); exit; }
$imagem = $row['imagem'];
$stmt->close();

// remover curtidas e comentarios (transação simples)
$conn->begin_transaction();
try {
    $stmt = $conn->prepare("DELETE FROM curtidas WHERE id_post = ?");
    $stmt->bind_param("i", $post_id); $stmt->execute(); $stmt->close();

    $stmt = $conn->prepare("DELETE FROM comentarios WHERE id_post = ?");
    $stmt->bind_param("i", $post_id); $stmt->execute(); $stmt->close();

    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $post_id); $stmt->execute(); $stmt->close();

    $conn->commit();
    // tenta apagar arquivo de imagem se existir
    if(!empty($imagem) && file_exists($imagem)) @unlink($imagem);
    echo json_encode(['status'=>'sucesso','mensagem'=>'Post excluído']);
} catch(Exception $e){
    $conn->rollback();
    echo json_encode(['status'=>'erro','mensagem'=>'Erro ao excluir']);
}
