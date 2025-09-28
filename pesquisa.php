<?php
include("conexao.php");

$q = $_GET['q'] ?? '';

if ($q) {
    $stmt = $conn->prepare("SELECT titulo, descricao FROM conteudos WHERE titulo LIKE ? OR descricao LIKE ?");
    $like = "%$q%";
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><meta charset="UTF-8"><title>Resultados da pesquisa</title></head>
<body>
<h2>Resultados para "<?php echo htmlspecialchars($q); ?>"</h2>
<?php if (!empty($result) && $result->num_rows > 0): ?>
  <ul>
    <?php while($row = $result->fetch_assoc()): ?>
      <li><strong><?php echo htmlspecialchars($row['titulo']); ?></strong> - <?php echo htmlspecialchars($row['descricao']); ?></li>
    <?php endwhile; ?>
  </ul>
<?php else: ?>
  <p>Nenhum resultado encontrado.</p>
<?php endif; ?>
</body>
</html>
