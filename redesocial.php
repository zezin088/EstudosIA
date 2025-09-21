<?php
include("conexao.php");
session_start();
$usuario_id = $_SESSION['usuario_id'] ?? 1;

// --- Buscar posts ---
$posts = [];
$sql = "SELECT p.id, p.usuario_id, p.conteudo, p.imagem, p.data_postagem, u.nome, u.foto,
               (SELECT COUNT(*) FROM curtidas c WHERE c.id_post = p.id) as total_curtidas,
               (SELECT COUNT(*) FROM curtidas c2 WHERE c2.id_post = p.id AND c2.id_usuario = ?) as curtiu
        FROM posts p
        JOIN usuarios u ON p.usuario_id = u.id
        ORDER BY p.data_postagem DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()){
    $post_id = $row['id'];
    $comentarios = [];
    $stmt2 = $conn->prepare("SELECT c.id, c.conteudo, c.data_comentario, u.nome, u.foto, c.id_usuario
                             FROM comentarios c
                             JOIN usuarios u ON c.id_usuario = u.id
                             WHERE c.id_post = ?
                             ORDER BY c.data_comentario ASC");
    $stmt2->bind_param("i", $post_id);
    $stmt2->execute();
    $res = $stmt2->get_result();
    while($row_c = $res->fetch_assoc()){
        $comentarios[] = $row_c;
    }
    $stmt2->close();
    $row['comentarios'] = $comentarios;
    $posts[] = $row;
}
$stmt->close();

// --- Lista de amigos (exemplo) ---
$amigos = [
    ['id'=>2,'nome'=>'Ana','foto'=>null,'ultimo_login'=>date('Y-m-d H:i:s')],
    ['id'=>3,'nome'=>'Bruno','foto'=>null,'ultimo_login'=>date('Y-m-d H:i:s', strtotime('-10 minutes'))],
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Rede Social - Estudos IA</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
body { background:#f0f4f8; color:#333; }

/* HEADER */
header {
  background:#e1efff;
  padding:15px 30px;
  display:flex;
  justify-content:space-between;
  align-items:center;
  border-bottom:1px solid #dbe5ef;
}
header h1 { font-size:24px; color:#1a3b5d; font-weight:700; }
header input[type="search"] {
  width:350px;
  padding:8px 15px;
  border-radius:15px;
  border:1px solid #c7d0d9;
}


/* CONTAINER */
.container { display:flex; gap:20px; padding:20px 30px; min-height:calc(100vh - 70px); }

.sidebar {
  width:260px;
  background:#fff;
  border-radius:20px;
  padding:25px 20px;
  box-shadow:0 4px 15px rgba(0,0,0,0.1);
  flex-shrink:0;
  position:fixed;
  left:32px;
  bottom:0;
  transform: translateY(100%);
  opacity: 0;
  pointer-events: none;
  transition: transform 0.4s cubic-bezier(.4,1.6,.4,1), opacity 0.3s;
  z-index: 3000;
}
.sidebar.active {
  transform: translateY(0);
  opacity: 1;
  pointer-events: auto;
}
.sidebar h3 { font-size:18px; margin-bottom:20px; color:#1a3b5d; }
.sidebar ul { list-style:none; }
.sidebar ul li { margin-bottom:15px; display:flex; align-items:center; cursor:pointer; color:#1a3b5d; padding:8px 12px; border-radius:12px; transition:all 0.2s ease; }
.sidebar ul li:hover { background:#e1efff; }
.sidebar ul li img { width:35px; height:35px; border-radius:50%; margin-right:12px; }

.sidebar-toggle {
  position: fixed;
  left: 32px;
  bottom: 32px;
  z-index: 3100;
  background: #1a73e8;
  color: #fff;
  border: none;
  border-radius: 12px 12px 0 0;
  padding: 16px 38px;
  font-size: 20px;
  font-weight: bold;
  box-shadow: 0 2px 8px rgba(44,44,84,0.10);
  cursor: pointer;
  transition: background 0.2s;
}
.sidebar-toggle:hover {
  background: #1665c1;
}

@media (max-width: 600px) {
  .sidebar, .sidebar.active {
    left: 0;
    width: 100vw;
    border-radius: 20px 20px 0 0;
    padding: 18px 8px;
  }
  .sidebar-toggle {
    left: 8px;
    bottom: 8px;
    width: calc(100vw - 16px);
    padding: 14px 0;
    font-size: 18px;
  }
}

.feed { flex:1; max-width:800px; }
.new-post { background:#fff; padding:20px; border-radius:20px; box-shadow:0 4px 15px rgba(0,0,0,0.05); margin-bottom:25px; }
.new-post textarea { width:100%; padding:15px; border-radius:15px; border:1px solid #c7d0d9; resize:none; outline:none; font-size:15px; background:#f5f7fa; }
.new-post button { margin-top:15px; padding:10px 20px; border:none; border-radius:15px; background:#1a73e8; color:white; cursor:pointer; font-weight:700; }
.new-post button:hover { background:#1665c1; }

.post { background:#fff; border-radius:20px; padding:20px; margin-bottom:25px; box-shadow:0 4px 12px rgba(0,0,0,0.05); }
.post-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:15px; }
.post-header-left { display:flex; align-items:center; gap:15px; }
.post-header-left img { width:50px; height:50px; border-radius:50%; }
.post-header-left .username { font-weight:700; color:#1a3b5d; font-size:16px; }
.post-header-left .time { font-size:12px; color:#7a7a7a; }
.post-menu { position:relative; cursor:pointer; font-size:20px; }
.menu-options { display:none; position:absolute; right:0; top:30px; background:#fff; border:1px solid #dbe5ef; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); }
.menu-options button { padding:10px 15px; width:100%; text-align:left; border:none; background:none; cursor:pointer; }
.menu-options button:hover { background:#e1efff; }
.post p { margin:10px 0; font-size:15px; line-height:1.6; color:#333; white-space:pre-wrap; }
.post img { width:100%; margin-top:12px; border-radius:15px; }

.actions { display:flex; gap:12px; margin-top:15px; }
.actions button { padding:6px 15px; border-radius:15px; border:none; cursor:pointer; font-size:14px; color:#1a73e8; background:#e6f0ff; }
.actions button:hover { background:#d0e3ff; }

.comentarios { margin-top:15px; }
.comentario { display:flex; align-items:flex-start; gap:12px; margin-bottom:12px; }
.comentario img { width:35px; height:35px; border-radius:50%; }
.comentario-content { background:#f5f7fa; padding:10px 15px; border-radius:15px; flex:1; }
.comentario-content p { font-size:14px; margin:0; }
.comentario-content .time { font-size:11px; color:#7a7a7a; text-align:right; margin-top:4px; }
.comentario-form { display:flex; gap:8px; margin-top:5px; }
.comentario-form input { flex:1; padding:8px 12px; border-radius:15px; border:1px solid #c7d0d9; outline:none; }
.comentario-form button { background:#1a73e8; color:white; border:none; border-radius:15px; padding:8px 15px; cursor:pointer; }
.comentario-form button:hover { background:#1665c1; }

/* CHAT FLUTUANTE */
.chat-sidebar {
  width:200px;
  background:#fff;
  border-left:1px solid #ddd;
  padding:10px;
  overflow-y:auto;
  position:fixed;
  right:0;
  top:70px;
  bottom:0;
  border-radius:10px 0 0 10px;
  z-index:1000;
}
.chat-sidebar ul { list-style:none; padding:0; margin:0; }
.chat-sidebar li { display:flex; align-items:center; cursor:pointer; margin-bottom:5px; }
.chat-sidebar li img { width:30px; height:30px; border-radius:50%; margin-right:5px; }
.status-dot { width:10px; height:10px; border-radius:50%; margin-left:auto; }
.status-dot.green { background:green; }
.status-dot.red { background:red; }

.chat-window {
  position:fixed;
  bottom:0;
  right:220px;
  width:300px;
  height:400px;
  background:white;
  border:1px solid #ccc;
  border-radius:10px;
  display:flex;
  flex-direction:column;
  box-shadow:0 0 10px rgba(0,0,0,0.2);
  z-index:1001;
  display:none;
}
.chat-header {
  padding:10px;
  background:#1a73e8;
  color:white;
  display:flex;
  justify-content:space-between;
  align-items:center;
  border-radius:10px 10px 0 0;
}
.chat-messages { flex:1; padding:10px; overflow-y:auto; background:#f5f7fa; }
.chat-messages .message { margin-bottom:10px; }
.chat-messages .message.sent { text-align:right; }
.chat-messages .message p { display:inline-block; padding:5px 10px; border-radius:10px; background:#e6f0ff; }
.chat-messages .message.sent p { background:#1a73e8; color:white; }
#chatForm { display:flex; border-top:1px solid #ddd; }
#chatInput { flex:1; padding:5px 10px; border:none; }
#chatForm button { background:#1a73e8; color:white; border:none; padding:5px 10px; cursor:pointer; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const sidebar = document.getElementById('sidebarMenu');
  const toggle = document.getElementById('sidebarToggle');
  let sidebarOpen = false;

  function closeSidebar(e) {
    if (sidebarOpen && !sidebar.contains(e.target) && e.target !== toggle) {
      sidebar.classList.remove('active');
      sidebarOpen = false;
      document.removeEventListener('mousedown', closeSidebar);
    }
  }

  toggle.addEventListener('click', function() {
    sidebarOpen = !sidebarOpen;
    sidebar.classList.toggle('active', sidebarOpen);
    if (sidebarOpen) {
      setTimeout(() => document.addEventListener('mousedown', closeSidebar), 10);
    } else {
      document.removeEventListener('mousedown', closeSidebar);
    }
  });
});
</script>
</head>
<body>
<header>
  <h1>Rede Social - Estudos IA</h1>
  <input type="search" placeholder="Pesquisar...">
</header>

<div class="container">
  <div class="sidebar" id="sidebarMenu">
    <h3>Menu</h3>
    <ul>
      <li><img src="imagens/usuarios/default.png" alt="Perfil">Feed</li>
      <li><img src="imagens/usuarios/default.png" alt="Perfil">Grupos de Estudo</li>
      <li><img src="imagens/usuarios/default.png" alt="Perfil">Materiais</li>
      <li><img src="imagens/usuarios/default.png" alt="Perfil">Eventos</li>
      <li><img src="imagens/usuarios/default.png" alt="Perfil">Ranking de Estudantes</li>
      <li><img src="imagens/usuarios/default.png" alt="Perfil">Configurações</li>
    </ul>
  </div>
  <button id="sidebarToggle" class="sidebar-toggle">☰ Menu</button>

  <div class="feed">
    <div class="new-post">
      <textarea placeholder="Compartilhe algo sobre seus estudos..."></textarea>
      <button onclick="addPost()">Publicar</button>
    </div>

    <div id="posts">
      <?php foreach($posts as $p): ?>
      <div class="post" data-id="<?= $p['id'] ?>">
        <div class="post-header">
          <div class="post-header-left">
            <img src="<?= $p['foto'] ?? 'imagens/usuarios/default.png' ?>" alt="Foto">
            <div>
              <div class="username"><?= htmlspecialchars($p['nome']) ?></div>
              <div class="time"><?= date('d/m/Y H:i', strtotime($p['data_postagem'])) ?></div>
            </div>
          </div>
          <?php if($p['usuario_id']==$usuario_id): ?>
          <div class="post-menu">⋮
            <div class="menu-options">
              <button onclick="deletePost(<?= $p['id'] ?>)">Excluir</button>
            </div>
          </div>
          <?php endif; ?>
        </div>
        <p><?= htmlspecialchars($p['conteudo']) ?></p>
        <?php if($p['imagem']): ?>
        <img src="uploads/<?= $p['imagem'] ?>" alt="Imagem do post">
        <?php endif; ?>
        <div class="actions">
          <button onclick="likePost(this, <?= $p['id'] ?>)">
            <?= $p['curtiu'] ? 'Curtir ❤️' : 'Curtir 🤍' ?> (<?= $p['total_curtidas'] ?>)
          </button>
        </div>
        <div class="comentarios">
          <?php foreach($p['comentarios'] as $c): ?>
          <div class="comentario">
            <img src="<?= $c['foto'] ?? 'imagens/usuarios/default.png' ?>" alt="Foto">
            <div class="comentario-content">
              <p><strong><?= htmlspecialchars($c['nome']) ?></strong>: <?= htmlspecialchars($c['conteudo']) ?></p>
              <div class="time"><?= date('d/m/Y H:i', strtotime($c['data_comentario'])) ?></div>
            </div>
          </div>
          <?php endforeach; ?>
          <form class="comentario-form" onsubmit="addComment(event, <?= $p['id'] ?>)">
            <input type="text" placeholder="Escreva um comentário..." required>
            <button>Enviar</button>
          </form>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Sidebar de amigos / chat -->
  <div class="chat-sidebar">
    <h4>Amigos</h4>
    <ul id="friendsList">
      <?php foreach($amigos as $amigo):
        $online = (strtotime($amigo['ultimo_login']) > time()-300);
      ?>
      <li data-id="<?= $amigo['id'] ?>" data-name="<?= htmlspecialchars($amigo['nome']) ?>">
        <img src="<?= $amigo['foto'] ?? 'imagens/usuarios/default.png' ?>" alt="Foto">
        <?= htmlspecialchars($amigo['nome']) ?>
        <span class="status-dot <?= $online?'green':'red' ?>"></span>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>

</div>

<!-- Chat flutuante -->
<div class="chat-window" id="chatWindow">
  <div class="chat-header">
    <span id="chatFriendName"></span>
    <button type="button" id="closeChatBtn">✖</button>
  </div>
  <div class="chat-messages" id="chatMessages"></div>
  <form id="chatForm" onsubmit="sendMessage(event)">
    <input type="text" id="chatInput" placeholder="Digite uma mensagem..." required>
    <button type="submit">Enviar</button>
  </form>
</div>

<script>
let currentFriendId = null;

// Chat lateral
document.addEventListener("DOMContentLoaded", ()=>{
  document.querySelectorAll("#friendsList li").forEach(li=>{
    li.addEventListener("click", ()=>{
      currentFriendId = li.getAttribute("data-id");
      document.getElementById("chatFriendName").innerText = li.getAttribute("data-name");
      document.getElementById("chatWindow").style.display='flex';
      loadMessages();
    });
  });
});
document.getElementById("closeChatBtn").addEventListener("click", ()=>{
  document.getElementById("chatWindow").style.display='none';
  currentFriendId = null;
});

// Funções Posts e Comentários
function likePost(btn, postId){
    let liked = btn.innerText.includes('❤️');
    let count = parseInt(btn.innerText.match(/\d+/)[0]);
    if(liked){ count--; btn.innerText=`Curtir 🤍 (${count})`; }
    else { count++; btn.innerText=`Curtir ❤️ (${count})`; }
    console.log("Curtir post:", postId, "Curtiu?", !liked);
}
function addPost(){
    const conteudo = document.getElementById("newPostContent").value.trim();
    if(!conteudo) return alert("Digite algo para publicar!");

    fetch('add_post.php', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'conteudo=' + encodeURIComponent(conteudo)
    })
    .then(res => res.text())
    .then(data => {
        if(data === 'ok'){
            document.getElementById("newPostContent").value = '';
            alert("Post publicado!");
            // opcional: atualizar feed via AJAX
        } else {
            alert("Erro ao publicar!");
        }
    });
}
function deletePost(id){ alert("Excluir post "+id); }
function addComment(e, postId){ e.preventDefault(); alert("Comentar no post "+postId); }

// Chat flutuante
function loadMessages(){ /* implementar AJAX */ }
function sendMessage(e){ e.preventDefault(); alert("Enviar mensagem para "+currentFriendId); }
</script>
</body>
</html>
