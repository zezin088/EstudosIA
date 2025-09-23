<?php
include("conexao.php");
session_start();
$is_embed = isset($_GET['embed']) && $_GET['embed'] == '1';
$usuario_id = $_SESSION['usuario_id'] ?? 1;

/**
 * Retorna URL de avatar segura:
 * - se for URL completa (http/https) retorna tal qual
 * - se for caminho relativo tenta resolver para arquivo no servidor
 * - caso contrário retorna avatar padrão
 */
function avatar_url($foto) {
    $default = 'imagens/usuarios/default.png';
    if (empty($foto)) return $default;

    // já é uma URL absoluta?
    if (preg_match('#^https?://#i', $foto)) return $foto;

    // caminho relativo: primeiro tenta exatamente, depois tenta na pasta imagens/usuarios
    $cand1 = __DIR__ . DIRECTORY_SEPARATOR . $foto;
    if (file_exists($cand1) && is_file($cand1)) return $foto;

    $cand2 = __DIR__ . DIRECTORY_SEPARATOR . 'imagens' . DIRECTORY_SEPARATOR . 'usuarios' . DIRECTORY_SEPARATOR . $foto;
    if (file_exists($cand2) && is_file($cand2)) return 'imagens/usuarios/' . basename($foto);

    return $default;
}

// busca dados do usuário atual
$stmtUser = $conn->prepare("SELECT id, nome, foto FROM usuarios WHERE id = ?");
if ($stmtUser) {
    $stmtUser->bind_param("i", $usuario_id);
    $stmtUser->execute();
    $resUser = $stmtUser->get_result();
    $current = $resUser->fetch_assoc() ?: ['id'=>$usuario_id,'nome'=>'Você','foto'=>'imagens/usuarios/default.png'];
    $stmtUser->close();
} else {
    $current = ['id'=>$usuario_id,'nome'=>'Você','foto'=>'imagens/usuarios/default.png'];
}

$foto_padrao = 'imagens/usuarios/default.png';

// --- Buscar posts (mantive sua query) ---
$posts = [];
$sql = "SELECT p.id, p.usuario_id, p.conteudo, p.imagem, p.data_postagem, u.nome, u.foto,
               (SELECT COUNT(*) FROM curtidas c WHERE c.id_post = p.id) as total_curtidas,
               (SELECT COUNT(*) FROM curtidas c2 WHERE c2.id_post = p.id AND c2.id_usuario = ?) as curtiu
        FROM posts p
        JOIN usuarios u ON p.usuario_id = u.id
        ORDER BY p.data_postagem DESC";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while($row = $result->fetch_assoc()){
        $post_id = $row['id'];
        $comentarios = [];
        // comentários
        $stmt2 = $conn->prepare("SELECT c.id, c.conteudo, c.data_comentario, u.nome, u.foto, c.id_usuario
                                 FROM comentarios c
                                 JOIN usuarios u ON c.id_usuario = u.id
                                 WHERE c.id_post = ?
                                 ORDER BY c.data_comentario ASC");
        if ($stmt2) {
            $stmt2->bind_param("i", $post_id);
            $stmt2->execute();
            $res = $stmt2->get_result();
            while($row_c = $res->fetch_assoc()){
                $comentarios[] = $row_c;
            }
            $stmt2->close();
        }
        $row['comentarios'] = $comentarios;
        $posts[] = $row;
    }
    $stmt->close();
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Rede Social — EstudosIA</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
:root{
  --bg:#fbfdfc;
  --card:#ffffff;
  --accent:#2f7b6f;
  --accent-2:#bdebe3;
  --muted:#6b7280;
  --glass-border: rgba(63,124,114,0.08);
  --radius:14px;
  --shadow: 0 10px 30px rgba(2,6,23,0.06);
}

/* reset */
*{box-sizing:border-box}
html,body{height:100%}
body{
  margin:0;
  font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, Arial;
  background: linear-gradient(180deg,#f6fbfb, #fff 60%);
  color:#0f172a;
  -webkit-font-smoothing:antialiased;
}

/* header limpo (sem logo) */
.header {
  position: sticky;
  top: 0;
  z-index: 1100;
  background: rgba(255,255,255,0.98);
  border-bottom: 1px solid var(--glass-border);
  display:flex;
  gap:12px;
  align-items:center;
  justify-content:center;
  padding:10px 12px;
}
.header .inner {
  width:100%;
  max-width:920px;
  display:flex;
  align-items:center;
  gap:12px;
  justify-content:space-between;
  padding:6px;
}
.header .search {
  flex:1; margin:0 12px; max-width:640px; display:flex; align-items:center;
  background:var(--card); padding:8px 12px; border-radius:999px; border:1px solid var(--glass-border);
  box-shadow: 0 8px 20px rgba(2,6,23,0.03);
}
.header .search input{ border:0; outline:none; width:100%; padding:8px; background:transparent; font-size:14px; color:#0f172a; }
.header .profile { display:flex; align-items:center; gap:10px; }

/* layout: feed central (instagram-like) */
.wrapper { display:flex; gap:28px; justify-content:center; padding:28px 16px 80px; }
.main { width:100%; max-width:640px; }
.rightcol { width:300px; display:none; }
@media(min-width:1024px){ .rightcol{ display:block; } }

/* create post */
.create {
  background:var(--card); border-radius:12px; box-shadow:var(--shadow); padding:12px; margin-bottom:18px;
  display:flex; gap:12px; align-items:flex-start;
}
.create .avatar { width:48px; height:48px; border-radius:10px; overflow:hidden; flex-shrink:0; }
.create .avatar img{ width:100%; height:100%; object-fit:cover; display:block; }
.create .inputs { flex:1; display:flex; flex-direction:column; gap:8px; }
.create textarea { width:100%; min-height:72px; padding:10px; border-radius:10px; border:1px solid var(--glass-border); font-size:14px; background:#fbfffe; resize:vertical; }

/* nicer file label */
.file-row { display:flex; align-items:center; justify-content:space-between; gap:8px; }
.file-label {
  padding:8px 12px; border-radius:10px; background:linear-gradient(180deg,#f4fffb,#eefaf6); border:1px solid var(--glass-border); cursor:pointer; color:var(--accent); display:inline-flex; gap:8px; align-items:center; font-weight:700;
}
.btn { padding:8px 14px; border-radius:999px; border:0; background:linear-gradient(180deg,var(--accent), #28594f); color:white; cursor:pointer; font-weight:700; box-shadow: 0 10px 30px rgba(47,123,111,0.08); }
.btn.ghost { background:transparent; color:var(--accent); border:1px solid rgba(63,124,114,0.08); }

/* post card */
.post { background:var(--card); border-radius:12px; margin-bottom:18px; box-shadow:var(--shadow); overflow:hidden; }
.post-header { display:flex; gap:12px; align-items:center; padding:12px; }
.post-header .avatar { width:44px; height:44px; border-radius:10px; overflow:hidden; flex-shrink:0; border:2px solid var(--glass-border); }
.post-header .avatar img{ width:100%; height:100%; object-fit:cover; }
.post-header .meta { display:flex; flex-direction:column; }
.post-header .meta .name { font-weight:700; color:var(--accent); }
.post-header .meta .time { font-size:13px; color:var(--muted); }

/* image */
.post-image { width:100%; background:#eee; display:block; }
.post-image img { width:100%; height:auto; max-height:80vh; object-fit:cover; display:block; }

/* action bar prettier */
.actions { display:flex; gap:8px; align-items:center; padding:10px 12px; }
.icon-btn {
  display:inline-grid;
  place-items:center;
  width:42px; height:36px;
  border-radius:10px;
  border:1px solid transparent;
  background:transparent;
  cursor:pointer;
  transition: all .14s ease;
  font-size:18px;
}
.icon-btn svg { width:20px; height:20px; display:block; }
.icon-btn:hover { transform:translateY(-3px); background:rgba(0,0,0,0.03); }
.icon-btn.like { color: #d33; }
.icon-btn.liked { background: linear-gradient(180deg,#ffdede,#ffbebe); color:#b12; border-color: rgba(177,34,34,0.08); }

/* likes + caption */
.likes { padding:0 12px 6px; font-weight:700; color:var(--accent); }
.caption { padding:0 12px 12px; color:#111; }

/* comments */
.comments { padding:0 12px 12px; display:flex; flex-direction:column; gap:8px; }
.comment { display:flex; gap:8px; align-items:flex-start; }
.comment .bubble { background:#fbfffe; padding:8px 12px; border-radius:12px; font-size:14px; color:#111; }
.comment-form { display:flex; gap:8px; padding:0 12px 14px; }
.comment-form input { flex:1; padding:9px 12px; border-radius:999px; border:1px solid var(--glass-border); background:#fff; }

/* right column (optional) */
.rightcol .card { background:var(--card); border-radius:12px; padding:12px; margin-bottom:12px; box-shadow:var(--shadow); }
.rightcol h4 { margin:0 0 8px 0; color:var(--muted); font-size:14px; }

.empty { text-align:center; color:var(--muted); padding:28px; }

/* mobile tweaks */
@media (max-width:640px) {
  .header .inner { padding:6px 8px; }
  .wrapper { padding:12px 10px 80px; }
  .main { max-width:100%; }
}
</style>
</head>
<body>
<?php if (!$is_embed): ?>
<!-- header: sem logo, somente busca e perfil -->
<div class="header">
  <div class="inner">
    <div class="search" role="search" aria-label="Pesquisar">
      <input type="search" placeholder="Pesquisar posts, pessoas..." id="globalSearch">
    </div>

    <div class="profile">
      <div style="text-align:right;font-weight:700;color:var(--muted);font-size:14px"><?php echo htmlspecialchars($current['nome']); ?></div>
      <div style="width:44px;height:44px;border-radius:10px;overflow:hidden;border:2px solid var(--glass-border)">
        <img src="<?php echo htmlspecialchars( avatar_url($current['foto']) ); ?>" alt="Avatar" style="width:100%;height:100%;object-fit:cover">
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
<!-- wrapper -->
<div class="wrapper">
  <main class="main">

    <!-- criar post -->
    <section class="create" aria-label="Criar publicação">
      <div class="avatar">
        <img src="<?php echo htmlspecialchars( avatar_url($current['foto']) ); ?>" alt="Você">
      </div>

      <div class="inputs">
        <form id="formPost" action="add_post.php" method="POST" enctype="multipart/form-data">
          <textarea id="postContent" name="conteudo" placeholder="Compartilhe algo sobre seus estudos..."></textarea>

          <div class="file-row">
            <div>
              <label class="file-label" for="fileUpload">
                <!-- pequeno ícone camera -->
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" style="vertical-align:middle">
                  <path d="M5 7h3l2-2h4l2 2h3v11a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V7z" stroke="#2f7b6f" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                  <circle cx="12" cy="13" r="3" stroke="#2f7b6f" stroke-width="1.2"/>
                </svg>
                <span style="margin-left:6px">Adicionar imagem</span>
              </label>
              <input id="fileUpload" name="imagem" type="file" accept="image/*" style="display:none">
            </div>

            <div style="display:flex;gap:8px">
              <button type="button" class="btn ghost" onclick="clearNewPost()">Limpar</button>
              <button type="submit" class="btn">Publicar</button>
            </div>
          </div>
        </form>
      </div>
    </section>

    <!-- feed -->
    <section id="posts">
      <?php if(empty($posts)): ?>
        <div class="empty">Ainda não há publicações.</div>
      <?php else: foreach($posts as $p): 
        $authorAvatar = avatar_url($p['foto'] ?? '');
      ?>
        <article class="post" data-id="<?php echo $p['id']; ?>">
          <div class="post-header">
            <div class="avatar"><img src="<?php echo htmlspecialchars($authorAvatar); ?>" alt="<?php echo htmlspecialchars($p['nome']); ?>"></div>
            <div class="meta">
              <div class="name"><?php echo htmlspecialchars($p['nome']); ?></div>
              <div class="time"><?php echo date('d/m/Y H:i', strtotime($p['data_postagem'])); ?></div>
            </div>
          </div>

          <?php if(!empty($p['imagem'])): ?>
            <div class="post-image"><img src="<?php echo htmlspecialchars('uploads/' . $p['imagem']); ?>" alt="Imagem do post"></div>
          <?php endif; ?>

          <div class="actions">
            <button class="icon-btn like <?php echo $p['curtiu'] ? 'liked' : ''; ?>" aria-pressed="<?php echo $p['curtiu']? 'true':'false'; ?>" onclick="toggleLike(this, <?php echo $p['id']; ?>)">
              <!-- heart SVG -->
              <?php if($p['curtiu']): ?>
                <svg viewBox="0 0 24 24" fill="#b12" xmlns="http://www.w3.org/2000/svg"><path d="M12.001 4.529c1.53-1.88 4.805-1.88 6.334 0 1.657 2.036 1.092 5.154-1.7 7.64L12 21.35l-4.635-9.182C5.573 9.683 5.008 6.565 6.665 4.529c1.529-1.88 4.804-1.88 6.336 0z"/></svg>
              <?php else: ?>
                <svg viewBox="0 0 24 24" fill="none" stroke="#2f7b6f" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 1 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z"/></svg>
              <?php endif; ?>
            </button>

            <button class="icon-btn" title="Comentar" onclick="document.getElementById('cinput-<?php echo $p['id']; ?>').focus()">
              <svg viewBox="0 0 24 24" fill="none" stroke="#2f7b6f" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            </button>

            <button class="icon-btn" title="Compartilhar" onclick="openShare(<?php echo $p['id']; ?>)">
              <svg viewBox="0 0 24 24" fill="none" stroke="#2f7b6f" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg"><path d="M4 12v7a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-7"/><path d="M16 6l-4-4-4 4"/><path d="M12 2v13"/></svg>
            </button>
          </div>

          <div class="likes" id="likes-<?php echo $p['id']; ?>"><?php echo (int)$p['total_curtidas']; ?> curtida<?php echo ((int)$p['total_curtidas'] !== 1 ? 's' : ''); ?></div>

          <?php if(trim($p['conteudo']) !== ''): ?>
            <div class="caption"><strong><?php echo htmlspecialchars($p['nome']); ?></strong> <?php echo nl2br(htmlspecialchars($p['conteudo'])); ?></div>
          <?php endif; ?>

          <div class="comments">
            <?php foreach($p['comentarios'] as $c): ?>
              <div class="comment">
                <div style="width:36px;height:36px;border-radius:8px;overflow:hidden"><img src="<?php echo htmlspecialchars( avatar_url($c['foto'] ?? '') ); ?>" alt="" style="width:100%;height:100%;object-fit:cover"></div>
                <div class="bubble"><strong><?php echo htmlspecialchars($c['nome']); ?></strong> <?php echo htmlspecialchars($c['conteudo']); ?></div>
              </div>
            <?php endforeach; ?>

            <form class="comment-form" onsubmit="submitComment(event, <?php echo $p['id']; ?>)">
              <input id="cinput-<?php echo $p['id']; ?>" type="text" placeholder="Adicione um comentário..." required>
            </form>
          </div>
        </article>
      <?php endforeach; endif; ?>
    </section>

  </main>

  <aside class="rightcol" aria-hidden="true">
    <div class="card" style="padding:14px; border-radius:12px; box-shadow:var(--shadow); background:var(--card);">
      <h4 style="margin:0 0 8px 0; color:var(--muted)">Sugestões</h4>
      <div style="display:flex;flex-direction:column;gap:10px">
        <div style="display:flex;gap:10px;align-items:center">
          <div style="width:44px;height:44px;border-radius:8px;background:#eee"></div>
          <div><strong>AnaBanana1</strong><div style="font-size:13px;color:var(--muted)">Siga</div></div>
        </div>
      </div>
    </div>
  </aside>
</div>

<script>
/* JS: comportamento (melhorado para atualização visual) */

function clearNewPost(){
  document.getElementById('postContent').value = '';
  document.getElementById('fileUpload').value = '';
}

/* like toggle: atualiza visual e manda request ao servidor (endpoint like.php) */
function toggleLike(btn, postId){
  const wasLiked = btn.classList.contains('liked');
  const likeSpan = document.getElementById('likes-' + postId);
  let count = parseInt((likeSpan.innerText.match(/\d+/)||['0'])[0]);

  if (wasLiked) {
    btn.classList.remove('liked');
    // trocar SVG para outline (simples: trocar innerHTML)
    btn.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="#2f7b6f" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 1 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z"/></svg>`;
    count = Math.max(0, count - 1);
  } else {
    btn.classList.add('liked');
    btn.innerHTML = `<svg viewBox="0 0 24 24" fill="#b12" xmlns="http://www.w3.org/2000/svg"><path d="M12.001 4.529c1.53-1.88 4.805-1.88 6.334 0 1.657 2.036 1.092 5.154-1.7 7.64L12 21.35l-4.635-9.182C5.573 9.683 5.008 6.565 6.665 4.529c1.529-1.88 4.804-1.88 6.336 0z"/></svg>`;
    count = count + 1;
  }

  likeSpan.innerText = count + (count === 1 ? ' curtida' : ' curtidas');

  // enviar ao servidor (não bloqueante)
  fetch('like.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'post_id=' + encodeURIComponent(postId)
  }).catch(()=>{/* ignore network errors */});
}

/* comment submit */
function submitComment(e, postId){
  e.preventDefault();
  const input = document.getElementById('cinput-' + postId);
  const text = input.value.trim();
  if(!text) return;
  fetch('add_comment.php', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: 'post_id=' + encodeURIComponent(postId) + '&conteudo=' + encodeURIComponent(text)
  }).then(r => r.text()).then(t => {
    if(t === 'ok') location.reload();
    else alert('Erro ao comentar: ' + t);
  }).catch(()=>alert('Erro de rede'));
}

/* compartilhar */
function openShare(postId){
  const url = window.location.origin + '/post.php?id=' + postId;
  if(navigator.clipboard){
    navigator.clipboard.writeText(url).then(()=> alert('Link copiado!'));
  } else {
    prompt('Copie o link:', url);
  }
}

/* enviar novo post: o form aponta para add_post.php; se quiser AJAX, descomente o bloco e implemente add_post.php retornando "ok" */
document.getElementById('formPost').addEventListener('submit', function(e){
  // opcional: envio tradicional via form. Se preferir AJAX, descomente:
  // e.preventDefault();
  // const fd = new FormData(this);
  // fetch('add_post.php', { method:'POST', body: fd }).then(r=>r.text()).then(t=> { if(t==='ok') location.reload(); else alert('Erro: '+t); });
});

/* upload label handler (mostra nome do arquivo curto) */
document.getElementById('fileUpload').addEventListener('change', function(){
  if(this.files && this.files[0]){
    document.querySelector('.file-label span').innerText = 'Imagem selecionada';
  } else {
    document.querySelector('.file-label span').innerText = 'Adicionar imagem';
  }
});

/* busca (enter) */
document.getElementById('globalSearch').addEventListener('keydown', function(e){
  if(e.key === 'Enter'){
    const q = this.value.trim(); if(!q) return;
    window.location.href = 'search.php?q=' + encodeURIComponent(q);
  }
});
</script>
</body>
</html>
