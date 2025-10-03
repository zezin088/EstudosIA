<?php
include("conexao.php");
if(isset($_GET['usuario_id'])){
    $usuario_id = intval($_GET['usuario_id']);
    $sql = "SELECT nome, avatar FROM usuarios WHERE id = $usuario_id";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $user = $result->fetch_assoc();
        echo '<img src="'.($user['avatar'] ?: 'default-avatar.png').'" style="width:100px;height:100px;border-radius:50%;"><br>';
        echo '<strong>'.htmlspecialchars($user['nome']).'</strong><br><br>';
        echo '<button>Seguir</button>';
    } else {
        echo 'Usuário não encontrado.';
    }
}
?>
