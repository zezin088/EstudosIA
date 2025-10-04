<?php
session_start();
include 'conexao.php';

// Redireciona se não estiver logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_logado = $_SESSION['usuario_id'];

// Buscar dados do usuário logado (nome e foto)
$stmt = $conn->prepare("SELECT nome, foto FROM usuarios WHERE id=?");
$stmt->bind_param("i", $usuario_logado);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows) {
    $usuario_logado_info = $res->fetch_assoc();
    $_SESSION['usuario_nome'] = $usuario_logado_info['nome'];
    $_SESSION['usuario_foto'] = $usuario_logado_info['foto'];
} else {
    $_SESSION['usuario_nome'] = 'Usuário';
    $_SESSION['usuario_foto'] = 'imagens/usuarios/default.jpg';
}

// Enviar post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['conteudo'])) {
    $conteudo = $_POST['conteudo'];
    $imagem = '';

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $filename = 'imagens/posts/' . uniqid() . '.' . ($ext ?: 'jpg');
        if (!is_dir(dirname($filename))) mkdir(dirname($filename), 0755, true);
        move_uploaded_file($_FILES['imagem']['tmp_name'], $filename);
        $imagem = $filename;
    }

    $stmt = $conn->prepare("INSERT INTO posts (usuario_id, conteudo, imagem) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $usuario_logado, $conteudo, $imagem);
    $stmt->execute();
    $stmt->close();

    header("Location: redesocial.php");
    exit();
}

// Buscar posts
$sql = "SELECT p.id AS post_id, p.conteudo, p.imagem, p.usuario_id,
        u.nome, u.foto
        FROM posts p
        JOIN usuarios u ON p.usuario_id = u.id
        ORDER BY p.id DESC";
$result = $conn->query($sql);

$posts = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}

// Modal perfil
if (isset($_GET['perfil_usuario_id'])) {
    $id = intval($_GET['perfil_usuario_id']);
    $stmt = $conn->prepare("SELECT nome, biografia, foto FROM usuarios WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows) {
        $u = $res->fetch_assoc();
        echo '<img src="'.htmlspecialchars($u['foto'] ?: 'imagens/usuarios/default.jpg').'" alt="foto" style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:2px solid #ccc;">';
        echo '<h3>'.htmlspecialchars($u['nome']).'</h3>';
        if (!empty($u['biografia'])) echo '<p>'.nl2br(htmlspecialchars($u['biografia'])).'</p>';
        if ($usuario_logado === $id) echo '<a href="editar_usuario.php">Editar Perfil</a>';
    } else {
        echo "Usuário não encontrado.";
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Rede Social</title>
<style>
body { font-family: Arial, sans-serif; background:#f0f2f5; margin:0; padding:0;}
header { background:#fff; padding:10px 20px; display:flex; justify-content:space-between; align-items:center; box-shadow:0 2px 5px rgba(0,0,0,.1);}
header a { text-decoration:none; color:#333; font-weight:600;}
header .usuario { display:flex; align-items:center; gap:10px; }
header .usuario img { width:40px; height:40px; border-radius:50%; object-fit:cover; border:2px solid #ccc; }

section#novo-post { max-width:600px; margin:20px auto; background:#fff; padding:15px; border-radius:10px; box-shadow:0 2px 5px rgba(0,0,0,.1);}
section#posts { max-width:600px; margin:20px auto; }
.post { background:#fff; padding:15px; margin-bottom:15px; border-radius:10px; box-shadow:0 2px 5px rgba(0,0,0,.1);}
.post img { width:100%; max-height:400px; object-fit:cover; border-radius:8px; margin-top:10px;}
.post .user { display:flex; align-items:center; gap:10px; cursor:pointer;}
.post .user img { width:50px; height:50px; border-radius:50%; object-fit:cover; border:2px solid #ccc;}
.modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:10000;}
.modal .content { background:#fff; padding:20px; border-radius:10px; max-width:400px; width:90%; text-align:center; position:relative;}
.modal .close { position:absolute; top:10px; right:15px; cursor:pointer; font-size:18px; font-weight:bold;}
input[type=text], textarea { width:100%; padding:8px; margin-bottom:10px; border-radius:8px; border:1px solid #ccc; }
button { background:#4a766e; color:white; padding:8px 12px; border:none; border-radius:6px; cursor:pointer; font-weight:600; }
</style>
</head>
<body>

<header>
    <a href="inicio.php">Início</a>
    <div class="usuario">
        <img src="<?= htmlspecialchars($_SESSION['usuario_foto'] ?? 'imagens/usuarios/default.jpg') ?>" alt="foto">
        <span>Bem-vindo(a) <?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário') ?></span>
    </div>
</header>

<section id="novo-post">
    <form method="POST" enctype="multipart/form-data">
        <textarea name="conteudo" placeholder="O que você está pensando?" required></textarea>
        <input type="file" name="imagem" accept="image/*">
        <button type="submit">Postar</button>
    </form>
</section>

<section id="posts">
<?php if(empty($posts)): ?>
    <div>Ainda não há publicações.</div>
<?php else: foreach($posts as $post): ?>
    <div class="post">
        <div class="user" onclick="abrirModal(<?= $post['usuario_id'] ?>)">
            <img src="<?= htmlspecialchars($post['foto'] ?: 'imagens/usuarios/default.jpg') ?>" alt="foto">
            <strong><?= htmlspecialchars($post['nome']) ?></strong>
        </div>
        <p><?= nl2br(htmlspecialchars($post['conteudo'])) ?></p>
        <?php if(!empty($post['imagem'])): ?>
            <img src="<?= htmlspecialchars($post['imagem']) ?>" alt="Imagem do post">
        <?php endif; ?>
    </div>
<?php endforeach; endif; ?>
</section>

<div id="modal" class="modal">
    <div class="content">
        <span class="close" onclick="fecharModal()">&times;</span>
        <div id="info-usuario">Carregando...</div>
    </div>
</div>

<script>
function abrirModal(usuarioId){
    fetch('perfil_ajax.php?perfil_usuario_id=' + usuarioId)
        .then(res => res.text())
        .then(data => {
            document.getElementById('info-usuario').innerHTML = data;
            document.getElementById('modal').style.display = 'flex';
        });
}

function fecharModal(){
    document.getElementById('modal').style.display = 'none';
}
</script>

</body>
</html>
