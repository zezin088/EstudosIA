<?php
include("conexao.php");
session_start();

$usuario_id = $_SESSION['usuario_id'] ?? 1;
$conteudo = trim($_POST['conteudo'] ?? '');
$imagemNome = null;

// --- Upload de imagem ---
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $tmpName = $_FILES['imagem']['tmp_name'];
    $originalName = basename($_FILES['imagem']['name']);
    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $permitidos = ['jpg','jpeg','png','gif'];

    if (in_array($ext, $permitidos)) {
        // nome único
        $imagemNome = time().'_'.preg_replace('/[^a-zA-Z0-9._-]/', '', $originalName);
        $destino = __DIR__ . '/uploads/' . $imagemNome;
        if (!move_uploaded_file($tmpName, $destino)) {
            die('Erro ao mover a imagem.');
        }
    } else {
        die('Formato de imagem não permitido.');
    }
}

// --- Inserir post no banco ---
$stmt = $conn->prepare("INSERT INTO posts (usuario_id, conteudo, imagem, data_postagem) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("iss", $usuario_id, $conteudo, $imagemNome);
if ($stmt->execute()) {
    header("Location: redesocial.php"); // volta pro feed
    exit;
} else {
    die("Erro ao salvar post: " . $stmt->error);
}
