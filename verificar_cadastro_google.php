<?php
$client_id = "912161681251-ak3vkdll5oknq0ssd0uv44ikpvq59q27.apps.googleusercontent.com";
$token = $_POST["credential"] ?? null;

if (!$token) {
    exit("Token não recebido.");
}

// Verifica token com o Google
$ch = curl_init("https://oauth2.googleapis.com/tokeninfo?id_token=" . $token);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

// Verificação segura do conteúdo
if (!$data || !isset($data["aud"])) {
    exit("Token inválido ou resposta do Google malformada.");
}

if ($data["aud"] !== $client_id) {
    exit("ID do cliente inválido.");
}

// Dados do usuário
$nome = $data["name"] ?? "Sem nome";
$email = $data["email"] ?? null;
$foto = $data["picture"] ?? null;

if (!$email) {
    exit("E-mail não encontrado.");
}

// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "bd_usuarios");
if ($conn->connect_error) {
    exit("Erro na conexão com o banco de dados.");
}

// Verifica se já existe
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Cadastra novo usuário
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, foto) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome, $email, $foto);
    if (!$stmt->execute()) {
        exit("Erro ao cadastrar usuário.");
    }
}

session_start();
$_SESSION["nome"] = $nome;
$_SESSION["email"] = $email;
$_SESSION["foto"] = $foto;

// Redirecionar após login
header("Location: login.php"); // ou a página para onde o usuário deve ir
exit();
?>
