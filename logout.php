<?php
session_start();

// Destrói todas as variáveis da sessão
$_SESSION = array();

// Se quiser apagar o cookie de sessão também
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000, // tempo no passado para apagar
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destrói a sessão
session_destroy();

// Redireciona para a página de login
header('Location: index.php');
exit();
?>
