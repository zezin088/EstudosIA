<?php
session_start();
include 'conexao.php';

// Verifica se o token foi enviado
if (!isset($_POST['token'])) {
    echo json_encode(['status' => 'erro', 'message' => 'Token não enviado']);
    exit;
}

// Recebe o token enviado do frontend
$token = $_POST['token'];

// Verifica a autenticidade do token com a API do Google
$clientId = "912161681251-ak3vkdll5oknq0ssd0uv44ikpvq59q27.apps.googleusercontent.com";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://oauth2.googleapis.com/tokeninfo?id_token=" . $token);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$dados = json_decode($response, true);

// Verifica se o token é válido e se o clientId bate
if (!isset($dados['email']) || $dados['aud'] !== $clientId) {
    echo json_encode(['status' => 'erro', 'message' => 'Token inválido ou não autorizado']);
    exit;
}

// Dados extraídos do token
$nome = $dados['name'];
$email = $dados['email'];
$google_id = $dados['sub'];

// Verifica se o usuário já existe no banco
$stmt = $conn->prepare("SELECT id, nome FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Usuário já existe
    $usuario = $result->fetch_assoc();
} else {
    // Usuário não encontrado, vamos criar um novo
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, google_id) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome, $email, $google_id);

    if ($stmt->execute()) {
        // Usuário criado com sucesso, agora pegamos os dados
        $usuario_id = $stmt->insert_id;  // Pegando o ID do novo usuário
        $usuario = ['id' => $usuario_id, 'nome' => $nome];
    } else {
        echo json_encode(['status' => 'erro', 'message' => 'Erro ao cadastrar usuário']);
        exit;
    }
}

// Salvar os dados na sessão
$_SESSION['usuario'] = $usuario['nome'];
$_SESSION['usuario_id'] = $usuario['id'];

echo json_encode([
    'status' => 'sucesso',
    'nome' => $usuario['nome'],
    'email' => $email
]);
?>
