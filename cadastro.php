<?php
include 'conexao.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // PHPMailer via Composer

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verifica se o e-mail já existe
    $check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>
            localStorage.setItem('mensagemLogin', 'Esse e-mail já está cadastrado!');
            window.location.href = 'login.html';
        </script>";
        exit;
    }

    // Cadastra o novo usuário
    $sql = $conn->prepare("INSERT INTO usuarios (nome, email, senha, verificado) VALUES (?, ?, ?, 0)");
    $sql->bind_param("sss", $nome, $email, $senha);

    if ($sql->execute()) {
        $user_id = $sql->insert_id;

        // Gerar código aleatório de 6 dígitos
        $codigo = rand(100000, 999999);

        // Salvar código no banco
        $stmt = $conn->prepare("INSERT INTO verificacoes (user_id, codigo) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $codigo);
        $stmt->execute();

        // Enviar e-mail com PHPMailer
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8'; // garante que acentos funcionem
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Gmail
            $mail->SMTPAuth   = true;
            $mail->Username   = 'estudosiatcc2025@gmail.com'; // seu Gmail
            $mail->Password   = 'utiy hhzs rzlr aona'; // senha de app gerada no Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('estudosiatcc2025@gmail.com', 'EstudosIA');
            $mail->addAddress($email, $nome);

            $mail->isHTML(true);
            $mail->Subject = 'Código de Verificação';
            $mail->Body    = "<h3>Olá, $nome!</h3>
                              <p>Seu código de verificação é: <b>$codigo</b></p>";
            $mail->send();
        } catch (Exception $e) {
            error_log("Erro ao enviar e-mail: {$mail->ErrorInfo}");
        }

        // Redireciona para verificar.html passando o user_id
        echo "<script>
            localStorage.setItem('mensagemLogin', 'Cadastrado com sucesso! Verifique seu e-mail.');
            window.location.href = 'verificar.html?user=$user_id';
        </script>";
    } else {
        echo "<script>
            localStorage.setItem('mensagemLogin', 'Erro ao cadastrar!');
            window.location.href = 'cadastro.html';
        </script>";
    }
}
?>
