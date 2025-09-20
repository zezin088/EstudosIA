<?php
$host = "localhost";
$user = "root";
$pass = ""; // senha do banco
$dbname = "bd_usuarios";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>
