<?php
// Conexão com o banco
$conn = new mysqli("localhost", "root", "", "bd_usuarios");
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Receber os dados do JavaScript
$dados = json_decode(file_get_contents("php://input"), true);

if (!$dados || !is_array($dados)) {
    die("Nenhum dado recebido.");
}

$usuario_id = 1; // ⚠️ Ajuste isso conforme o ID do usuário logado

$stmt = $conn->prepare("INSERT INTO financas (usuario_id, data, descricao, valor, tipo) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issds", $usuario_id, $data, $descricao, $valor, $tipo);

foreach ($dados as $item) {
    $data = $item['data'];
    $descricao = $item['descricao'];
    $valor = $item['valor'];
    $tipo = $item['tipo'];
    $stmt->execute();
}

$stmt->close();
$conn->close();

echo "Dados salvos com sucesso!";
?>
