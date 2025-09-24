<?php
session_start();
include("conexao.php");

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

/* --- Buscar dados do usuário --- */
$sql = "SELECT nome, username, biografia, foto, data_criacao, favoritos, tags, data_nascimento, escola, foto_pessoal 
        FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($nome, $username, $biografia, $foto, $data_criacao, $favoritos, $tags, $data_nascimento, $escola, $foto_pessoal);
$stmt->fetch();
$stmt->close();

/* --- Calcular tempo de conta --- */
$data_inicio = new DateTime($data_criacao);
$hoje = new DateTime();
$tempo_conta = $data_inicio->diff($hoje)->days;

/* --- Buscar tempo estudado no cronômetro --- */
$sqlTempo = "SELECT SUM(tempo_estudado) FROM estudo WHERE usuario_id = ?";
$stmt = $conn->prepare($sqlTempo);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($tempo_estudado_total);
$stmt->fetch();
$stmt->close();

$horas = floor($tempo_estudado_total / 3600);
$minutos = floor(($tempo_estudado_total % 3600) / 60);

/* --- Buscar posts do usuário --- */
$sqlPosts = "SELECT imagem FROM posts WHERE usuario_id = ?";
$stmt = $conn->prepare($sqlPosts);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultPosts = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Perfil - EstudosIA</title>
<style>
@font-face { font-family: 'SimpleHandmade'; src: url(/fonts/SimpleHandmade.ttf); }

body {
    font-family: Arial, sans-serif;
    background: #fffaf0;
    color: #4b3c2f;
    margin: 0;
    padding: 20px;
}

.perfil-container {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.col-left, .col-right {
    flex: 1;
    min-width: 300px;
}

/* Cards fofinhos */
.card {
    background: #f3e4c9;
    border-radius: 20px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 6px 12px rgba(0,0,0,0.08);
    border: 1px solid #c49c76;
    position: relative;
}

.card:not(:last-child)::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 10px;
    width: calc(100% - 20px);
    height: 1px;
    background: #c49c76;
    opacity: 0.4;
}

/* Foto e nome */
.foto-perfil img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 3px solid #c49c76;
    object-fit: cover;
    margin-bottom: 10px;
}

.nome-usuario h1 {
    font-family: 'SimpleHandmade';
    font-size: 2em;
    margin: 5px 0;
}
.nome-usuario h2 {
    font-size: 1em;
    color: #a67c52;
    margin: 0;
}

/* Texto editável */
.editable {
    cursor: pointer;
    padding: 5px 0;
}
.editable:hover {
    background: rgba(63,124,114,0.1);
    border-radius: 5px;
}

/* Botões pequenos coloridos */
.btn-color {
    display: inline-block;
    padding: 5px 12px;
    margin: 3px 3px 3px 0;
    border-radius: 12px;
    border: none;
    cursor: pointer;
    font-size: 0.9em;
    color: white;
    transition: transform 0.2s;
}
.btn-color:hover {
    transform: translateY(-2px);
}

/* Preferências */
.tags {
    display: flex;
    flex-wrap: wrap;
}

/* Posts */
.grid-posts {
    display: grid;
    grid-template-columns: repeat(auto-fill,minmax(100px,1fr));
    gap: 10px;
}
.grid-posts img {
    width: 100%;
    border-radius: 12px;
    object-fit: cover;
}

/* Relatório */
.relatorio {
    font-size: 0.95em;
    line-height: 1.5;
}
</style>
</head>
<body>

<div class="perfil-container">
    <!-- Coluna esquerda -->
    <div class="col-left">
        <div class="card foto-nome">
            <div class="foto-perfil">
                <img src="<?php echo !empty($foto) ? $foto : 'imagens/usuarios/default.jpg'; ?>" alt="Foto de perfil">
            </div>
            <div class="nome-usuario">
                <h1 class="editable" contenteditable="true"><?php echo htmlspecialchars($nome); ?></h1>
                <h2 class="editable" contenteditable="true">@<?php echo htmlspecialchars($username); ?></h2>
            </div>
        </div>

        <div class="card preferencias">
            <h3>Preferências</h3>
            <div class="tags">
                <?php
                $opcoes = ["Jogos","Leitura","Culinária","Programação","Postagens","Música","Esportes"];
                $tagsUsuario = explode(",", $tags);
                foreach ($opcoes as $tag) {
                    $cor = in_array($tag,$tagsUsuario) ? "#3f7c72" : "#c49c76";
                    echo "<button class='btn-color' style='background:$cor;'>$tag</button>";
                }
                ?>
            </div>
        </div>

        <div class="card biografia">
            <h3>Biografia</h3>
            <div class="editable" contenteditable="true"><?php echo htmlspecialchars($biografia); ?></div>
        </div>

        <div class="card extras">
            <div class="editable" contenteditable="true">Data de nascimento: <?php echo htmlspecialchars($data_nascimento); ?></div>
            <div class="editable" contenteditable="true">Escola/Faculdade: <?php echo htmlspecialchars($escola); ?></div>
            <div class="editable" contenteditable="true">Favoritos: <?php echo htmlspecialchars($favoritos); ?></div>
            <div class="editable" contenteditable="true">Foto pessoal: <?php echo htmlspecialchars($foto_pessoal); ?></div>
        </div>
    </div>

    <!-- Coluna direita -->
    <div class="col-right">
        <div class="card posts">
            <h3>Posts do Usuário</h3>
            <div class="grid-posts">
                <?php while($post = $resultPosts->fetch_assoc()): ?>
                    <img src="<?php echo $post['imagem']; ?>" alt="Post">
                <?php endwhile; ?>
            </div>
        </div>

        <div class="card relatorio">
            <h3>Relatório de Horas Estudadas</h3>
            <p>⏳ Tempo total estudado: <strong><?php echo $horas."h ".$minutos."min"; ?></strong></p>
            <p>📅 Conta criada há: <strong><?php echo $tempo_conta; ?> dias</strong></p>
        </div>
    </div>
</div>

</body>
</html>
