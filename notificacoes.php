<?php
session_start();
include("conexao.php");

$usuario_id = $_SESSION['usuario_id'] ?? 0;

$stmt = $conn->prepare("SELECT mensagem, data_criacao FROM notificacoes WHERE usuario_id = ? ORDER BY data_criacao DESC LIMIT 5");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    echo "<p><strong>".htmlspecialchars($row['mensagem'])."</strong><br><small>".$row['data_criacao']."</small></p>";
  }
} else {
  echo "<p>Sem notificações.</p>";
}
