<?php
require_once 'db.php';
// e $pdo deve existir aqui
var_dump($pdo instanceof PDO); // deve mostrar bool(true)

$host = "127.0.0.1";
$user = "root"; // usuário padrão do XAMPP
$pass = "";     // senha padrão do XAMPP (geralmente vazia)
$db   = "bd_usuarios";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco: " . $e->getMessage());
}
?>
