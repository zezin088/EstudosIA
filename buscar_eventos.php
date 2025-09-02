<?php
$host = "localhost";
$user = "root";
$senha = ""; // sua senha
$banco = "nome_do_seu_banco"; // coloque o nome do banco

$conn = new mysqli($host, $user, $senha, $banco);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$sql = "SELECT * FROM eventos";
$result = $conn->query($sql);

$eventos = [];

while ($row = $result->fetch_assoc()) {
    $eventos[] = $row;
}

echo json_encode($eventos);

$conn->close();
?>