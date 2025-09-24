<?php
include 'config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = intval($_POST['usuario_id']);
    $semana = intval($_POST['semana']);
    $itens = json_decode($_POST['itens'], true);

    if(!$itens) $itens = [];

    $conteudo = json_encode($itens, JSON_UNESCAPED_UNICODE);

    // Verifica se já existe
    $sql = "SELECT id FROM plano_estudos WHERE usuario_id = ? AND semana = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $usuario_id, $semana);
    $stmt->execute();
    $res = $stmt->get_result();

    if($res->num_rows > 0){
        $row = $res->fetch_assoc();
        $update = "UPDATE plano_estudos SET conteudo = ? WHERE id = ?";
        $stmt2 = $conn->prepare($update);
        $stmt2->bind_param("si", $conteudo, $row['id']);
        $stmt2->execute();
        echo json_encode(["status"=>"atualizado"]);
    } else {
        $insert = "INSERT INTO plano_estudos (usuario_id, semana, conteudo) VALUES (?, ?, ?)";
        $stmt3 = $conn->prepare($insert);
        $stmt3->bind_param("iis", $usuario_id, $semana, $conteudo);
        $stmt3->execute();
        echo json_encode(["status"=>"salvo"]);
    }
}
?>