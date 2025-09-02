<?php
session_start();

// ========================= CONFIG =========================
$uploadDir = __DIR__ . '/uploads';
$maxSize   = 10 * 1024 * 1024; // 10 MB
$allowedMime = ['application/pdf'];
$allowedExt  = ['pdf'];

// CSRF simples
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}
$csrf = $_SESSION['csrf'];

// Cria pasta se não existir + .htaccess
if (!is_dir($uploadDir)) {
    @mkdir($uploadDir, 0775, true);
}
$htaccess = $uploadDir . '/.htaccess';
if (!file_exists($htaccess)) {
    @file_put_contents($htaccess, "Options -Indexes\nRemoveHandler .php\nRemoveType .php\n");
}

// Helper p/ feedback
function flash($msg, $type='ok'){
    $_SESSION['flash'][] = ['t'=>$type, 'm'=>$msg];
}
if (!isset($_SESSION['flash'])) $_SESSION['flash'] = [];

// ========================= BANCO DE DADOS (PDO) =========================
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=bd_usuarios;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro na conexão com o banco: " . $e->getMessage());
}

// ========================= UPLOAD HANDLER =========================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['do_upload'])) {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        flash('Sessão expirada. Atualize a página e tente novamente.', 'err');
        header('Location: '.$_SERVER['REQUEST_URI']); exit;
    }

    if (!isset($_FILES['pdf']) || $_FILES['pdf']['error'] !== UPLOAD_ERR_OK) {
        flash('Nenhum arquivo selecionado ou erro no envio.', 'err');
        header('Location: '.$_SERVER['REQUEST_URI']); exit;
    }

    $f = $_FILES['pdf'];

    if ($f['size'] > $maxSize) {
        flash('Arquivo muito grande. Limite de 10 MB.', 'err');
        header('Location: '.$_SERVER['REQUEST_URI']); exit;
    }

    // Verifica MIME real
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($f['tmp_name']);
    $ext   = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));

    if (!in_array($mime, $allowedMime, true) || !in_array($ext, $allowedExt, true)) {
        flash('Apenas arquivos PDF são permitidos.', 'err');
        header('Location: '.$_SERVER['REQUEST_URI']); exit;
    }

    // Gera nome limpo e único
    $base = preg_replace('/[^a-z0-9\-_. ]+/i', '_', pathinfo($f['name'], PATHINFO_FILENAME));
    $destName = $base . '_' . date('Ymd_His') . '_' . bin2hex(random_bytes(3)) . '.pdf';
    $destPath = $uploadDir . '/' . $destName;

    if (!move_uploaded_file($f['tmp_name'], $destPath)) {
        flash('Falha ao salvar o arquivo.', 'err');
    } else {
        flash('PDF enviado com sucesso!', 'ok');
        // Aqui você pode salvar info no banco se quiser:
        // $stmt = $pdo->prepare("INSERT INTO arquivos (nome_arquivo) VALUES (?)");
        // $stmt->execute([$destName]);
    }

    header('Location: '.$_SERVER['REQUEST_URI']); exit;
}

// ========================= DELETE HANDLER =========================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_file'])) {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        flash('Sessão expirada. Atualize a página e tente novamente.', 'err');
        header('Location: '.$_SERVER['REQUEST_URI']); exit;
    }

    $filename = basename($_POST['delete_file']);
    $filepath = $uploadDir . '/' . $filename;

    if (is_file($filepath) && strtolower(pathinfo($filepath, PATHINFO_EXTENSION)) === 'pdf') {
        if (@unlink($filepath)) {
            flash('Arquivo excluído com sucesso!', 'ok');
            // Aqui também pode deletar do banco se você usar
        } else {
            flash('Não foi possível excluir o arquivo.', 'err');
        }
    } else {
        flash('Arquivo inválido ou inexistente.', 'err');
    }

    header('Location: '.$_SERVER['REQUEST_URI']); exit;
}

// ========================= LISTA DE ARQUIVOS =========================
$files = [];
if (is_dir($uploadDir)) {
    foreach (glob($uploadDir.'/*.pdf') as $p) {
        $files[] = [
            'name' => basename($p),
            'size' => filesize($p),
            'mtime'=> filemtime($p),
            'url'  => 'uploads/'.rawurlencode(basename($p)),
        ];
    }
    // mais novo primeiro
    usort($files, fn($a,$b)=> $b['mtime'] <=> $a['mtime']);
}

function humanSize($bytes){
    $u = ['B','KB','MB','GB','TB'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($u)-1){ $bytes/=1024; $i++; }
    return number_format($bytes, ($i?1:0), ',', '.') . ' ' . $u[$i];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Upload PDF</title>

<!-- ========================= ESTILO COMPLETO ========================= -->
<style>
  /* ========= PALETA & BASE ========= */
:root{
  --bg: #ffe3d9;          /* pêssego claro */
  --paper: #f7d9d4;       /* papel rosado */
  --terracotta: #7a3a3a;  /* marrom avermelhado */
  --terracotta-700:#5c2a2a;
  --terracotta-900:#3c1d1d;
  --chip: #ead6cf;
  --ink: #2a1a1a;
  --white: #fff;
  --shadow: 0 8px 18px rgba(60,29,29,.25);
  --radius: 22px;
}
*{box-sizing:border-box}
body{
  margin:0;
  font-family: "Inter", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
  background: var(--bg);
  color: var(--ink);
}

/* ========= HOTBAR ========= */
header{
  position: sticky; top:0; z-index:10;
  border-bottom: 1px solid #edd0c9;
  box-shadow: 0 2px 8px rgba(0,0,0,.05);
}
.navbar{
  max-width: 1100px; margin: 0 auto;
  height: 72px; padding: 0 20px;
  display:flex; align-items:center; justify-content:space-between;
  gap:16px;
}
.brand{
  display:flex; align-items:center; gap:12px;
}
.brand img{ height:44px; display:block }
.nav-links{
  display:flex; align-items:center; gap:22px; font-weight:600;
}
.nav-links a{
  color: var(--terracotta-700); text-decoration:none;
  padding:8px 12px; border-radius:14px;
}
.nav-links a:hover{ background: var(--chip); }
.user-chip{
  display:flex; align-items:center; gap:10px;
  background: var(--chip); border:1px solid #e4c7bf;
  padding:8px 12px; border-radius:999px; font-weight:600;
  color: var(--terracotta-900);
}
.avatar{
  width:30px; height:30px; border-radius:50%;
  background: linear-gradient(180deg,#ffe9e1,#f4cbc0);
  border:1px solid #e6bdb3;
  display:grid; place-items:center; font-size:12px; color:var(--terracotta-900);
}

/* Barra atualizada para marrom */
.barra {
  background: #5c3a2a; /* marrom sólido */
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 14px 30px;
  box-shadow: 0 4px 8px rgba(90,59,44,0.3);
  color: white;
  font-family: 'Raesha';
  position: sticky;
  top: 0;
  z-index: 100;
}

.barra h1 {
  font-family: 'Raesha', cursive;
  font-size: 36px;
  margin: 0;
  color: floralwhite;
}

nav ul {
  display: flex;
  list-style: none;
  gap: 25px;
  margin: 0;
  padding: 0;
}

nav ul li a {
  text-decoration: none;
  color: #fff5f5;
  font-weight: 600;
  font-size: 18px;
  transition: color 0.3s ease, border-bottom 0.3s;
  border-bottom: 2px solid transparent;
}

nav ul li a:hover {
  color:rgba(176, 106, 106, 0.3);
  border-bottom: 2px solid rgba(176, 106, 106, 0.3);
}

/* ========= CONTEÚDO ========= */
.container{
  max-width: 1100px; margin: 24px auto 80px; padding: 0 24px;
}
h1{
  color: var(--terracotta-700);
  font-size: 28px;
  margin: 24px 0 14px;
  text-align:center;
}

/* Bloco de envio */
.upload-card{
  background:#fff8f4;
  border:1px solid #edd0c9;
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  padding: 22px;
  margin-bottom: 18px;
}
.upload-row{
  display:flex; gap:12px; align-items:center; justify-content:center; flex-wrap:wrap;
}
input[type=file]{
  font-size:16px; background:#fff; border:1px solid #d9b8b8; border-radius:10px; padding:8px;
}

/* Botões */
.btn{
  background: linear-gradient(180deg, var(--terracotta) 0%, var(--terracotta-700) 100%);
  color:#fff; border:none; padding:12px 20px; cursor:pointer;
  font-weight:800; border-radius:999px;
  box-shadow: 0 8px 16px rgba(122,58,58,.25);
}
.btn:hover{ filter:brightness(1.05) }
.btn:active{ transform: translateY(1px) }

.btn.danger {
  background: linear-gradient(180deg, #912e2e 0%, #641f1f 100%);
  color: #fff;
  padding: 8px 14px;
  font-weight: 700;
  border: none;
  border-radius: 999px;
  cursor: pointer;
  box-shadow: 0 6px 14px rgba(100, 31, 31, 0.3);
}
.btn.danger:hover {
  filter: brightness(1.1);
}

/* Botão Baixar atualizado */
.btn-download {
  background: linear-gradient(180deg, #5c2a2a 0%, #5c2a2a 100%);
  color: #fff !important; /* forçar branco */
  padding: 8px 14px;
  font-weight:700;
  border: none;
  border-radius: 999px;
  cursor: pointer;
  box-shadow: 0 6px 14px rgba(90,59,44,0.3);
  text-decoration: none; /* remove sublinhado se houver */
}

.btn-download:hover {
  filter: brightness(1.05);
}

/* Alerts */
.alerts{ margin: 12px 0 0 }
.alert{
  padding:10px 14px; border-radius:12px; font-weight:600; margin-bottom:8px;
}
.alert.ok{ background:#e6f6eb; color:#114b26; border:1px solid #bfe7c9 }
.alert.err{ background:#fde8e7; color:#6d1815; border:1px solid #f1b7b4 }

/* Tabela */
.table-wrap{
  background:#fff8f4; border:1px solid #edd0c9; border-radius:16px; overflow:hidden;
  box-shadow: 0 4px 12px rgba(0,0,0,.06);
}
table.table-pdf{
  width:100%; border-collapse:collapse; color: var(--terracotta-700);
}
.table-pdf thead{ background: var(--terracotta-700); color:#fff }
.table-pdf th, .table-pdf td{ padding:12px 14px }
.table-pdf thead th{ font-weight:700 }
.table-pdf tbody tr{ border-bottom:1px solid  #e9c9c2 ; }
.table-pdf tbody tr:hover{ background: var(--paper) }
.table-pdf td.nome{ font-weight:700; color:#a94545 }
.table-pdf a{ color:inherit; text-decoration:none }
.table-pdf a:hover{ text-decoration:underline }

/* Upload wrapper */
.file-upload-wrapper {
  position: relative;
  overflow: hidden;
  display: inline-block;
  border-radius: 999px;
  background: linear-gradient(180deg, var(--terracotta) 0%, var(--terracotta-700) 100%);
  color: #fff;
  font-weight: 800;
  padding: 12px 20px;
  cursor: pointer;
  box-shadow: 0 8px 16px rgba(122,58,58,.25);
  transition: filter 0.2s ease;
}
.file-upload-wrapper:hover {
  filter: brightness(1.05);
}
.file-upload-wrapper input[type="file"] {
  font-size: 100px;
  position: absolute;
  left: 0;
  top: 0;
  opacity: 0;
  cursor: pointer;
  height: 100%;
  width: 100%;
  border: none;
  margin: 0;
  padding: 0;
}

.btn-action {
  background: linear-gradient(180deg, #6b4c3b 0%, #5a3b2c 100%);
  color: #fff !important;
  padding: 8px 14px;
  font-weight:700;
  border: none;
  border-radius: 999px;
  cursor: pointer;
  box-shadow: 0 6px 14px rgba(90,59,44,0.3);
  text-decoration: none;
}

.btn-action:hover {
  filter: brightness(1.05);
}
</style>
</head>
<body>

<!-- ========================= HOTBAR ========================= -->
<header class="barra">
  <h1>Estudos IA</h1>
  <nav>
    <ul>
      <li><a href="../inicio.php">Início</a></li>
      <li><a href="../sobre_nos.html">Sobre</a></li>
    </ul>
  </nav>
</header>

<main class="container">
  <h1>Envie seu arquivo PDF</h1>

  <section class="upload-card">
    <form method="post" enctype="multipart/form-data" class="upload-row">
      <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf, ENT_QUOTES); ?>">
      <label class="file-upload-wrapper">
        <input type="file" name="pdf" accept="application/pdf" required> Escolher Arquivo
      </label>
      <button class="btn" type="submit" name="do_upload" value="1">Enviar PDF</button>
    </form>
  </section>

  <?php if (!empty($_SESSION['flash'])): ?>
    <div class="alerts">
      <?php foreach ($_SESSION['flash'] as $f): ?>
        <div class="alert <?php echo $f['t']==='ok'?'ok':'err'; ?>">
          <?php echo $f['m']; ?>
        </div>
      <?php endforeach; $_SESSION['flash']=[]; ?>
    </div>
  <?php endif; ?>

  <section class="table-wrap">
    <table class="table-pdf">
      <thead>
        <tr>
          <th style="width:45%">Nome</th>
          <th style="width:15%">Tamanho</th>
          <th style="width:20%">Modificado</th>
          <th style="width:20%">Ação</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!$files): ?>
          <tr><td colspan="4">Nenhum PDF enviado ainda.</td></tr>
        <?php else: foreach ($files as $it): ?>
          <tr>
            <td class="nome">
              <a href="<?php echo htmlspecialchars($it['url'], ENT_QUOTES); ?>" target="_blank" rel="noopener">
                <?php echo htmlspecialchars($it['name'], ENT_QUOTES); ?>
              </a>
            </td>
            <td><?php echo humanSize($it['size']); ?></td>
            <td><?php echo date('d/m/Y H:i', $it['mtime']); ?></td>
            <td style="display:flex; gap:10px; align-items:center;">

              <!-- Botão Baixar -->
              <a class="btn-action"
                 href="<?php echo htmlspecialchars($it['url'], ENT_QUOTES); ?>" download>
                 Baixar
              </a>

              <!-- Botão Excluir -->
              <form method="post" style="margin:0" onsubmit="event.preventDefault(); showConfirm(this);">
                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf, ENT_QUOTES); ?>">
                <input type="hidden" name="delete_file" value="<?php echo htmlspecialchars($it['name'], ENT_QUOTES); ?>">
                <button type="submit" class="btn-action">Excluir</button>
              </form>

            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </section>
</main>

<!-- Modal de confirmação -->
<div id="confirmModal" class="modal">
  <div class="modal-content">
    <p>Tem certeza que deseja excluir este arquivo?</p>
    <div class="modal-buttons">
      <button id="cancelBtn" class="btn-action">Cancelar</button>
      <button id="confirmBtn" class="btn-action">Excluir</button>
    </div>
  </div>
</div>

<footer>
  © <?php echo date('Y'); ?> — Sistema de PDFs
</footer>

<!-- ========================= ESTILO ADICIONAL ========================= -->
<style>
/* Botões Baixar e Excluir */
.btn-action {
  background: linear-gradient(180deg, #5c2a2a 0%, #5c2a2a 100%);
  color: #fff !important;
  padding: 12px 20px;       
  font-weight: 700;
  border: none;
  border-radius: 999px;
  cursor: pointer;
  box-shadow: 0 6px 14px rgba(90,59,44,0.3);
  text-decoration: none;
  display: inline-block;
  min-width: 100px;        
  text-align: center;      
}

.btn-action:hover {
  filter: brightness(1.05);
}

/* Modal */
.modal {
  display: none;
  position: fixed;
  top:0; left:0; right:0; bottom:0;
  background: rgba(0,0,0,0.5);
  justify-content: center;
  align-items: center;
  z-index: 1000;
}
.modal-content {
  background: #fff8f4;
  padding: 24px;
  border-radius: 22px;
  box-shadow: 0 8px 18px rgba(60,29,29,.25);
  text-align: center;
  max-width: 400px;
  font-family: "Inter", system-ui, sans-serif;
}
.modal-buttons {
  margin-top: 20px;
  display: flex;
  justify-content: center;
  gap: 12px;
}
</style>

<!-- ========================= SCRIPT ========================= -->
<script>
function showConfirm(form) {
  const modal = document.getElementById('confirmModal');
  modal.style.display = 'flex';

  const confirmBtn = document.getElementById('confirmBtn');
  const cancelBtn = document.getElementById('cancelBtn');

  // Remove eventos anteriores
  confirmBtn.onclick = null;
  cancelBtn.onclick = null;

  confirmBtn.onclick = function() {
    form.submit();
  }
  cancelBtn.onclick = function() {
    modal.style.display = 'none';
  }
}
</script>

</body>
</html>