<?php
session_start();
include 'conexao.php';
if (!isset($_SESSION['usuario_id'])) header("Location: login.php");

$usuario_id = $_GET['id'] ?? $_SESSION['usuario_id'];

// Buscar dados do usuário
$stmt = $pdo->prepare("SELECT nome, bio, favoritos, avatar FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$usuario) die("Usuário não encontrado.");

// Buscar posts do usuário
$stmt = $pdo->prepare("SELECT * FROM posts WHERE usuario_id = ? ORDER BY id DESC");
$stmt->execute([$usuario_id]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Perfil de <?= htmlspecialchars($usuario['nome']) ?></title>
<style>
body{font-family:Arial,sans-serif;background:#f0f2f5;margin:0;padding:0;}
.container{width:500px;margin:50px auto;background:white;padding:25px;border-radius:10px;box-shadow:0 4px 10px rgba(0,0,0,0.1);text-align:center;}
.container img{width:150px;height:150px;border-radius:50%;object-fit:cover;border:2px solid #ccc;}
h2{margin-top:15px;color:#333;}
p{font-size:15px;color:#555;}
button{margin-top:20px;padding:12px 20px;border:none;border-radius:6px;background:#4CAF50;color:white;font-size:16px;cursor:pointer;}
button:hover{background:#45a049;}
a{color:white;text-decoration:none;}
.post{background:#fff;padding:15px;margin-top:20px;border-radius:10px;box-shadow:0 3px 6px rgba(0,0,0,.1);text-align:left;}
.post img{width:100%;max-height:400px;object-fit:cover;border-radius:10px;margin-top:10px;}
</style>
</head>
<body>
<div class="container">
<img src="<?= htmlspecialchars($usuario['avatar'] ?? 'https://via.placeholder.com/150') ?>" alt="Avatar">
<h2><?= htmlspecialchars($usuario['nome']) ?></h2>
<?php if(!empty($usuario['bio'])): ?><p><strong>Bio:</strong> <?= nl2br(htmlspecialchars($usuario['bio'])) ?></p><?php endif; ?>
<?php if(!empty($usuario['favoritos'])): ?><p><strong>Favoritos:</strong> <?= htmlspecialchars($usuario['favoritos']) ?></p><?php endif; ?>

<?php if($_SESSION['usuario_id'] == $usuario_id): ?>
<button><a href="editar_perfil.php">Editar Perfil</a></button>
<?php endif; ?>

<?php if($posts): ?>
    <h3 style="margin-top:30px;text-align:left;">Posts de <?= htmlspecialchars($usuario['nome']) ?>:</h3>
    <?php foreach($posts as $post): ?>
        <div class="post">
            <p><?= nl2br(htmlspecialchars($post['conteudo'])) ?></p>
            <?php if($post['imagem']): ?>
                <img src="<?= htmlspecialchars($post['imagem']) ?>" alt="Imagem do post">
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p style="margin-top:20px;">Ainda não há posts.</p>
<?php endif; ?>

</div>
</body>
</html>