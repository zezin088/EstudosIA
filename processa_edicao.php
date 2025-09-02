<?php
session_start();
include 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
  header('Location: login.html');
  exit();
}

// Obtém os dados enviados
$id = $_SESSION['id']; // ID do usuário logado
$nome = $_POST['nome'];
$email = $_POST['email'];
$biografia = $_POST['biografia'];

// Se houver uma nova foto
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $foto = $_FILES['foto'];
    $foto_nome = uniqid() . '_' . $foto['name'];
    $foto_temp = $foto['tmp_name'];
    $foto_destino = 'imagens/usuarios/' . $foto_nome;

    move_uploaded_file($foto_temp, $foto_destino);
} else {
    $foto_destino = $_POST['foto_antiga']; // Manter a foto atual se não houver upload
}

// Atualiza o banco de dados com os novos dados
$sql = "UPDATE usuarios SET nome = '$nome', email = '$email', biografia = '$biografia', foto = '$foto_destino' WHERE id = '$id'";

if ($conn->query($sql) === TRUE) {
    echo "Perfil atualizado com sucesso!";
    header("Location: index.php"); // Redireciona para a página inicial (index.php)
    exit();
} else {
    echo "Erro ao atualizar perfil: " . $conn->error;
}

$conn->close();
?>