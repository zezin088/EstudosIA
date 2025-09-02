<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$host = "localhost";
$user = "root";
$pass = "";
$db   = "bd_usuarios";

$conn = new mysqli($host, $user, $pass, $db);
if($conn->connect_error) die("Falha na conexão: " . $conn->connect_error);

$mensagem = "";

if(isset($_POST['email'])) {
    $email = $conn->real_escape_string($_POST['email']);
    
    $result = $conn->query("SELECT nome FROM usuarios WHERE email='$email'");
    if($result->num_rows == 1){
        $row = $result->fetch_assoc();
        $nome = $row['nome'];
        
        $token = bin2hex(random_bytes(16));
        $conn->query("UPDATE usuarios SET token='$token' WHERE email='$email'");
        
        $link = "http://localhost/TesteTCC/nova_senha.php?token=".$token;
        
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'anabeatrizmarquescezar@gmail.com';
            $mail->Password   = 'mnfu qikv rrmd uzuh';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->CharSet = 'UTF-8';

            $mail->setFrom('anabeatrizmarquescezar@gmail.com', 'Projeto TCC Estudos IA');
            $mail->addAddress($email, $nome);

            $mail->isHTML(true);
            $mail->Subject = 'Recuperação de Senha';
            $mail->Body    = "
            <html>
            <head>
            <style>
            body { font-family: Arial; background-color: #f3e4c9; color: #333; padding: 20px;}
            .container { background-color: white; padding: 20px; border-radius: 10px; max-width: 600px; margin: auto; box-shadow: 0 4px 10px rgba(0,0,0,0.1);}
            h2 { color: #c06262; }
            a { background-color: #c06262; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; }
            </style>
            </head>
            <body>
            <div class='container'>
            <h2>Recuperação de Senha</h2>
            <p>Olá, <strong>{$nome}</strong>! Recebemos uma solicitação para redefinir sua senha.</p>
            <p>Clique no botão abaixo para criar uma nova senha:</p>
            <p><a href='{$link}' target='_blank'>Redefinir Senha</a></p>
            <p>Se você não solicitou, ignore esta mensagem.</p>
            <p>Atenciosamente,<br>Equipe EstudosIA</p>
            </div>
            </body>
            </html>
            ";
            $mail->AltBody = "Olá, {$nome}! Acesse este link para redefinir sua senha: {$link}";
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->send();
            $mensagem = "Mensagem enviada! Verifique seu email.";
        } catch (Exception $e) {
            $mensagem = "Erro ao enviar e-mail: {$mail->ErrorInfo}";
        }
    } else {
        $mensagem = "Email não cadastrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Recuperar Senha</title>
<style>
@font-face { font-family: raesha; src: url('fonts/Raesha.ttf') format('truetype'); }

body {
  font-family: Arial, sans-serif;
  background: rgb(243,228,201);
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

.container {
  background: white;
  padding: 30px;
  border-radius: 15px;
  width: 350px;
  text-align: center;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

h2 { color: rgb(139,80,80); font-family: 'raesha'; margin-bottom: 20px; }

input {
  width: 80%;
  padding: 12px;
  margin: 10px 0;
  border: 2px solid rgb(192,98,98);
  border-radius: 25px;
  font-size: 1rem;
  outline: none;
}

input:focus { border-color: rgb(139,80,80); }

button {
  width: 50%;
  padding: 12px;
  background-color: rgb(192,98,98);
  color:white;
  border:none;
  border-radius:25px;
  font-size:1.1rem;
  cursor:pointer;
  margin-top:10px;
}

button:hover { background-color: rgb(139,80,80); }

.mensagem {
  margin: 15px 0;
  padding: 10px;
  border-radius: 5px;
  background-color: #d4edda;
  color: #155724;
  font-weight: bold;
}
</style>
</head>
<body>
<div class="container">
  <h2>Recuperar Senha</h2>
  <?php if($mensagem) echo "<div class='mensagem'>{$mensagem}</div>"; ?>
  <form method="POST">
    <input type="email" name="email" placeholder="Digite seu e-mail" required>
    <button type="submit">Recuperar</button>
  </form>
</div>
</body>
</html>
