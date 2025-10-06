<?php
session_start();
include 'conexao.php';

$usuario_id = intval($_SESSION['usuario_id'] ?? 0);

$stmt = $conn->prepare("
    SELECT u.id, u.nome, u.foto
    FROM usuarios u
    JOIN amizades a ON (a.id_usuario1 = ? AND a.id_usuario2 = u.id) OR (a.id_usuario2 = ? AND a.id_usuario1 = u.id)
    WHERE u.last_online > NOW() - INTERVAL 5 MINUTE
    ORDER BY u.nome ASC
");
$stmt->bind_param("ii", $usuario_id, $usuario_id);
$stmt->execute();
$res = $stmt->get_result();
$amigos = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if(!$amigos){
    echo '<div>Nenhum amigo online</div>';
    exit;
}

foreach($amigos as $a){
    $foto = $a['foto'] ?: 'imagens/usuarios/default.jpg';
    echo '<div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">';
    echo '<img src="'.htmlspecialchars($foto).'" style="width:32px;height:32px;border-radius:50%;object-fit:cover;border:2px solid #3f7c72;">';
    echo '<div>'.htmlspecialchars($a['nome']).' <span style="color:green;font-weight:bold;">●</span></div>';
    echo '</div>';
}
?>
