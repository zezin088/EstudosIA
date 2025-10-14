<?php
include 'conexao.php';

// Recebe os dados do JavaScript (formato JSON)
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
  die("Nenhum dado recebido");
}

// Apaga os dados anteriores
$conn->query("DELETE FROM agenda");

// Salva os novos compromissos
foreach ($data['dias'] as $dia) {
  $stmt = $conn->prepare("INSERT INTO agenda (dia, compromisso, horario) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $dia['nome'], $dia['compromisso'], $dia['horario']);
  $stmt->execute();
}

// Atualiza notas gerais
if (isset($data['notas'])) {
  $stmt = $conn->prepare("INSERT INTO agenda (dia, notas) VALUES ('notas', ?)");
  $stmt->bind_param("s", $data['notas']);
  $stmt->execute();
}

echo "✔️ Agenda salva com sucesso!";

$conn->close();
?>
