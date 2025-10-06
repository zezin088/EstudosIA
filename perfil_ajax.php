<?php
include("conexao.php");
session_start();

if (!isset($_GET['perfil_usuario_id'])) exit('ID inválido');
$usuario_id = intval($_GET['perfil_usuario_id']);
$usuario_logado = $_SESSION['usuario_id'] ?? 0;

// Pega informações do usuário
$sql = "SELECT * FROM usuarios WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();

    $foto = !empty($user['foto']) ? htmlspecialchars($user['foto']) : 'imagens/usuarios/default.jpg';
    $banner = !empty($user['banner']) ? htmlspecialchars($user['banner']) : 'imagens/usuarios/default-banner.jpg';
    $bio_foto = !empty($user['bio_foto']) ? htmlspecialchars($user['bio_foto']) : 'imagens/bio/default.jpg';
    $biografia = !empty($user['biografia']) ? nl2br(htmlspecialchars($user['biografia'])) : 'Sem biografia';
    $aniversario = !empty($user['aniversario']) ? date('d/m/Y', strtotime($user['aniversario'])) : 'Não informado';
    $favoritos = !empty($user['favoritos']) ? explode(',', $user['favoritos']) : [];
    $online = $user['online'] ? true : false;

    echo '<div class="perfil-modal">';
    echo '<div class="perfil-banner"><img src="'.$banner.'" alt="Banner"></div>';
    echo '<div class="perfil-foto-container">';
    echo '<img src="'.$foto.'" class="perfil-avatar">';
    if($online) echo '<span class="online-badge" title="Online"></span>';
    echo '</div>';

    echo '<h2>'.htmlspecialchars($user['nome']);
    if (!empty($user['apelido'])) echo ' <small>('.htmlspecialchars($user['apelido']).')</small>';
    echo '</h2>';

    echo '<div class="counts">
            <div class="item">✨ Favoritos: '.count($favoritos).'</div>
          </div>';

    echo '<div class="about">';
    echo '<img class="bio-photo" src="'.$bio_foto.'" alt="Foto Bio">';
    echo '<p><strong>Bio:</strong> '.$biografia.'</p>';
    echo '<p><strong>🎂 Aniversário:</strong> '.$aniversario.'</p>';
    echo '<div class="favoritos-box"><strong>Favoritos:</strong><ul>';
    if(!empty($favoritos)){
        foreach($favoritos as $f) echo '<li>'.htmlspecialchars(trim($f)).'</li>';
    } else { echo '<li>Sem favoritos</li>'; }
    echo '</ul></div></div>';

    // Se for o próprio usuário
    if ($usuario_logado == $usuario_id) {
        echo '<button class="btn-editar" onclick="window.location.href=\'editar_usuario.php\'">Editar Perfil</button>';
    } else {
        // Verifica se já é amigo
        $stmt2 = $conn->prepare("SELECT * FROM amizades WHERE (id_usuario1=? AND id_usuario2=?) OR (id_usuario1=? AND id_usuario2=?)");
        $stmt2->bind_param("iiii", $usuario_logado, $usuario_id, $usuario_id, $usuario_logado);
        $stmt2->execute();
        $res2 = $stmt2->get_result();
        $ja_amigo = $res2->num_rows > 0;
        $stmt2->close();

        if($ja_amigo){
            echo '<button class="btn-editar" onclick="removerAmigo('.$usuario_id.')">Remover Amigo</button>';
        } else {
            echo '<button class="btn-editar" onclick="adicionarAmigo('.$usuario_id.')">Adicionar Amigo</button>';
        }
    }

    echo '</div>';

    // CSS do modal
    echo '<style>
    .perfil-modal { font-family: "Inter", sans-serif; background: #fff; padding: 1rem; width: 90%; max-width: 500px; max-height: 80vh; margin: 3% auto; border-radius: 16px; box-shadow: 0 6px 20px rgba(0,0,0,0.15); text-align: center; overflow-y: auto; position: relative; }
    .perfil-banner { width: 100%; height: 140px; border-radius: 12px 12px 0 0; overflow: hidden; margin-bottom: -60px; }
    .perfil-banner img { width: 100%; height: 100%; object-fit: cover; }
    .perfil-foto-container { position: relative; display: inline-block; margin-bottom: 0.5rem; }
    .perfil-avatar { width: 120px; height: 120px; border-radius: 50%; border: 4px solid #4a766e; object-fit: cover; }
    .online-badge { width: 16px; height: 16px; background: #2ecc71; border-radius: 50%; position: absolute; right: 0; bottom: 0; border: 2px solid #fff; }
    h2 { margin: 0.5rem 0; color: #2c544f; font-weight: 700; }
    .counts { display:flex; justify-content:center; gap:10px; margin-bottom:0.6rem; color:#4a766e; font-weight:600; }
    .counts .item { background: rgba(74,118,110,0.07); padding:4px 8px; border-radius:10px; font-size:0.9rem; }
    .about { text-align:left; margin-top:0.6rem; }
    .bio-photo { width:100%; height:140px; object-fit:cover; border-radius:12px; margin-bottom:0.5rem; }
    .favoritos-box { background:#e0f2f1; border-radius:10px; padding:0.5rem; margin-top:0.6rem; }
    .favoritos-box ul { margin:0.3rem 0 0 1rem; padding:0; }
    .favoritos-box li { list-style:disc; }
    .btn-editar { margin-top:0.8rem; padding:0.5rem 1rem; border:none; border-radius:12px; cursor:pointer; font-weight:600; background:#f0f0f0; color:#4a766e; }
    </style>';

} else {
    echo 'Usuário não encontrado.';
}
?>
