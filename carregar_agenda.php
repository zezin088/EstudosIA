<?php
include 'conexao.php';

$result = $conn->query("SELECT * FROM agenda");
$agenda = [];

while ($row = $result->fetch_assoc()) {
  $agenda[] = $row;
}

echo json_encode($agenda);

$conn->close();
?>
