<?php
session_start();
include 'conexao.php';
$usuario_logado = $_SESSION['usuario_id'];
$destinatario = intval($_POST['destinatario']);

// Inserir solicitação
$stmt = $conn->prepare("INSERT INTO solicitacoes_amizade (id_remetente, id_destinatario) VALUES (?, ?)");
$stmt->bind_param("ii",$usuario_logado,$destinatario);
$stmt->execute();
$id_solicitacao = $stmt->insert_id;
$stmt->close();

// Inserir notificação
$mensagem = 'enviou uma solicitação de amizade';
$stmt = $conn->prepare("INSERT INTO notificacoes (usuario_id, tipo, referencia_id, mensagem) VALUES (?, 'amizade', ?, ?)");
$stmt->bind_param("iis",$destinatario,$id_solicitacao,$mensagem);
$stmt->execute();
$stmt->close();

echo json_encode(['status'=>'sucesso','mensagem'=>'Solicitação enviada!']);
