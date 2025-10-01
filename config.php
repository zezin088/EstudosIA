<?php
$servername = "localhost";
$username = "root"; // seu usuário MySQL
$password = "";     // sua senha
$dbname = "bd_usuarios";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
