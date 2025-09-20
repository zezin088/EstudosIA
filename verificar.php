<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id']);
    $codigo  = trim($_POST['codigo']); // remove espaços

    // Busca o código mais recente do usuário
    $stmt = $conn->prepare("
        SELECT codigo FROM verificacoes 
        WHERE user_id = ? 
        ORDER BY criado_em DESC 
        LIMIT 1
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $codigo_banco = $row['codigo'];

        if ($codigo === $codigo_banco) {
            // Código correto → atualizar verificado
            $update = $conn->prepare("UPDATE usuarios SET verificado = 1 WHERE id = ?");
            $update->bind_param("i", $user_id);
            $update->execute();

            echo "<script>
                alert('Conta verificada com sucesso!');
                window.location.href = 'login.php';
            </script>";
            exit;
        }
    }

    // Se chegou aqui → código inválido
    echo "<script>
        alert('Código inválido. Tente novamente.');
        window.location.href = 'verificar.html?user=$user_id';
    </script>";
}
?>
