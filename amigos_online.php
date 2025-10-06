<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id'])) exit('Não logado');

$usuario_id = $_SESSION['usuario_id'];

// pega amigos
$sql = "SELECT u.id, u.nome, u.foto, u.online 
        FROM amizades a
        JOIN usuarios u ON (u.id = a.id_usuario1 OR u.id = a.id_usuario2)
        WHERE (a.id_usuario1 = ? OR a.id_usuario2 = ?) 
          AND u.id != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $usuario_id,$usuario_id,$usuario_id);
$stmt->execute();
$res = $stmt->get_result();

while($amigo = $res->fetch_assoc()){
    if($amigo['online']){ // precisa ter coluna `online` em usuarios
        $foto = !empty($amigo['foto']) ? $amigo['foto'] : 'imagens/usuarios/default.jpg';
        echo "<div style='display:flex;align-items:center;gap:10px;margin-bottom:8px;'>
                <img src='".htmlspecialchars($foto)."' style='width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid green;'>
                <div>".htmlspecialchars($amigo['nome'])."</div>
              </div>";
    }
}
$stmt->close();
