<?php
session_start();
include 'config.php';

// ------------------- Usuário logado -------------------
// Pegando o usuário da sessão
if (!isset($_SESSION['usuario_id'])) {
    die("Você precisa estar logado para acessar esta página.");
}
$usuario_id = $_SESSION['usuario_id'];

// ------------------- Adicionar plano -------------------
if (isset($_POST['acao']) && $_POST['acao'] === 'add') {
    $semana = intval($_POST['semana']);
    $atividades = $_POST['atividades'];

    try {
        $stmt = $pdo->prepare("INSERT INTO planos (usuario_id, semana, atividades) VALUES (:usuario_id, :semana, :atividades)");
        $stmt->execute([
            ':usuario_id' => $usuario_id,
            ':semana' => $semana,
            ':atividades' => $atividades
        ]);
        header("Location: plano_estudos.php");
        exit;
    } catch (PDOException $e) {
        die("Erro ao salvar plano: " . $e->getMessage());
    }
}

// ------------------- Editar plano -------------------
if (isset($_POST['acao']) && $_POST['acao'] === 'edit') {
    $id = intval($_POST['id']);
    $semana = intval($_POST['semana']);
    $atividades = $_POST['atividades'];

    try {
        $stmt = $pdo->prepare("UPDATE planos SET semana = :semana, atividades = :atividades WHERE id = :id AND usuario_id = :usuario_id");
        $stmt->execute([
            ':semana' => $semana,
            ':atividades' => $atividades,
            ':id' => $id,
            ':usuario_id' => $usuario_id
        ]);
        header("Location: plano_estudos.php");
        exit;
    } catch (PDOException $e) {
        die("Erro ao atualizar plano: " . $e->getMessage());
    }
}

// ------------------- Excluir plano -------------------
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    try {
        $stmt = $pdo->prepare("DELETE FROM planos WHERE id = :id AND usuario_id = :usuario_id");
        $stmt->execute([
            ':id' => $id,
            ':usuario_id' => $usuario_id
        ]);
        header("Location: plano_estudos.php");
        exit;
    } catch (PDOException $e) {
        die("Erro ao excluir plano: " . $e->getMessage());
    }
}

// ------------------- Buscar planos do usuário -------------------
try {
    $stmt = $pdo->prepare("SELECT * FROM planos WHERE usuario_id = :usuario_id ORDER BY semana ASC");
    $stmt->execute([':usuario_id' => $usuario_id]);
    $planos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar planos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Plano de Estudos</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 90%; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background: #eee; }
        form { margin-top: 20px; }
        textarea { width: 100%; }
        input[type="number"] { width: 60px; }
        .actions a { margin-right: 10px; }
    </style>
</head>
<body>
    <h1>Plano de Estudos</h1>

    <!-- Adicionar Plano -->
    <h2>Adicionar Plano</h2>
    <form method="POST">
        <input type="hidden" name="acao" value="add">
        Semana: <input type="number" name="semana" required>
        <br><br>
        Atividades:<br>
        <textarea name="atividades" rows="4" required></textarea>
        <br><br>
        <button type="submit">Salvar Plano</button>
    </form>

    <!-- Listagem de Planos -->
    <h2>Planos Salvos</h2>
    <table>
        <tr>
            <th>Semana</th>
            <th>Atividades</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($planos as $plano): ?>
        <tr>
            <td><?= htmlspecialchars($plano['semana']) ?></td>
            <td><?= nl2br(htmlspecialchars($plano['atividades'])) ?></td>
            <td class="actions">
                <a href="?edit=<?= $plano['id'] ?>">Editar</a>
                <a href="?delete=<?= $plano['id'] ?>" onclick="return confirm('Deseja realmente excluir?')">Excluir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <!-- Formulário de Edição -->
    <?php
    if (isset($_GET['edit'])):
        $edit_id = intval($_GET['edit']);
        try {
            $stmt = $pdo->prepare("SELECT * FROM planos WHERE id = :id AND usuario_id = :usuario_id");
            $stmt->execute([':id' => $edit_id, ':usuario_id' => $usuario_id]);
            $plano_edit = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erro ao buscar plano para edição: " . $e->getMessage());
        }

        if ($plano_edit):
    ?>
        <h2>Editar Plano - Semana <?= $plano_edit['semana'] ?></h2>
        <form method="POST">
            <input type="hidden" name="acao" value="edit">
            <input type="hidden" name="id" value="<?= $plano_edit['id'] ?>">
            Semana: <input type="number" name="semana" value="<?= $plano_edit['semana'] ?>" required>
            <br><br>
            Atividades:<br>
            <textarea name="atividades" rows="4" required><?= htmlspecialchars($plano_edit['atividades']) ?></textarea>
            <br><br>
            <button type="submit">Atualizar Plano</button>
        </form>
    <?php 
        endif;
    endif; 
    ?>
</body>
</html>
