<?php
session_start();
include("conexao.php");

if(!isset($_SESSION['usuario_id'])) exit;
$usuario_id = $_SESSION['usuario_id'];
$friendId = $_GET['friendId'];

$stmt = $conn->prepare("SELECT id_remetente as remetente, conteudo FROM mensagens
                        WHERE (id_remetente=? AND id_destinatario=?) OR (id_remetente=? AND id_destinatario=?)
                        ORDER BY data_envio ASC");
$stmt->bind_param("iiii", $usuario_id, $friendId, $friendId, $usuario_id);
$stmt->execute();
$res = $stmt->get_result();
$messages = [];
while($row = $res->fetch_assoc()){
    $messages[] = $row;
}
$stmt->close();

echo json_encode($messages);
?>
