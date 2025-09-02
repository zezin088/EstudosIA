<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);
ob_start();

ob_clean();
header('Content-Type: application/json');
echo json_encode(['status' => 'teste', 'message' => 'Funcionando!']);
exit;