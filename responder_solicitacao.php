<?php
session_start();
include 'conexao.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status'=>'erro','mensagem'=>'Não logado']);
    exit();
}

$usuario_id = intval($_SESSION['usuario_id']);
$id_solicitacao = intval($_POST['id_solicitacao'] ?? 0);
$resposta = strtolower(trim($_POST['resposta'] ?? ''));

if (!$id_solicitacao) {
    echo json_encode(['status'=>'erro','mensagem'=>'Solicitação inválida']);
    exit();
}

// Busca solicitação
$stmt = $conn->prepare("SELECT id, id_remetente, id_destinatario FROM solicitacoes_amizade WHERE id = ?");
$stmt->bind_param("i", $id_solicitacao);
$stmt->execute();
$res = $stmt->get_result();
if(!$res || $res->num_rows === 0){
    echo json_encode(['status'=>'erro','mensagem'=>'Solicitação não encontrada']);
    exit();
}
$sol = $res->fetch_assoc();
$stmt->close();

// Verifica se o usuário é o destinatário da solicitação
if ($sol['id_destinatario'] != $usuario_id) {
    echo json_encode(['status'=>'erro','mensagem'=>'Você não pode responder a esta solicitação']);
    exit();
}

if ($resposta === 'aceita') {
    // Cria amizade (garantindo que a amizade não exista)
    $stmt = $conn->prepare("SELECT id FROM amizades WHERE (id_usuario1 = ? AND id_usuario2 = ?) OR (id_usuario1 = ? AND id_usuario2 = ?)");
    $stmt->bind_param("iiii", $sol['id_remetente'], $sol['id_destinatario'], $sol['id_destinatario'], $sol['id_remetente']);
    $stmt->execute();
    $res = $stmt->get_result();
    $existe = $res->num_rows > 0;
    $stmt->close();

    if (!$existe) {
        $stmt = $conn->prepare("INSERT INTO amizades (id_usuario1, id_usuario2) VALUES (?, ?)");
        $stmt->bind_param("ii", $sol['id_remetente'], $sol['id_destinatario']);
        $stmt->execute();
        $stmt->close();
    }

    // Remove solicitação
    $stmt = $conn->prepare("DELETE FROM solicitacoes_amizade WHERE id = ?");
    $stmt->bind_param("i", $id_solicitacao);
    $stmt->execute();
    $stmt->close();

    echo json_encode([
        'status'=>'sucesso',
        'mensagem'=>'Solicitação aceita',
        'notif_id'=>$id_solicitacao,
        'novo_amigo_id'=>$sol['id_remetente']
    ]);

} elseif ($resposta === 'recusada') {
    // Apenas remove solicitação
    $stmt = $conn->prepare("DELETE FROM solicitacoes_amizade WHERE id = ?");
    $stmt->bind_param("i", $id_solicitacao);
    $stmt->execute();
    $stmt->close();

    echo json_encode([
        'status'=>'sucesso',
        'mensagem'=>'Solicitação recusada',
        'notif_id'=>$id_solicitacao
    ]);

} else {
    echo json_encode(['status'=>'erro','mensagem'=>'Resposta inválida']);
}
