<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['session_active' => false]);
    exit();
}

echo json_encode([
    'session_active' => true,
    'usuario_id' => $_SESSION['usuario_id'],
    'username' => $_SESSION['username'] ?? ''
]);
