<?php
require_once '../includes/db.php';
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode([]);
    exit();
}

$query = $_POST['query'] ?? '';

// Use the $conn variable directly from db.php which sets up the PDO connection

$search_query = "SELECT id, username, nombre_completo, avatar FROM usuarios
                WHERE (username LIKE :query OR nombre_completo LIKE :query)
                AND id != :user_id
                AND es_publico = 1
                LIMIT 10";

$stmt = $conn->prepare($search_query);
$search_param = "%$query%";
$stmt->bindParam(':query', $search_param);
$stmt->bindParam(':user_id', $_SESSION['usuario_id']);
$stmt->execute();

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Asegurar que el avatar tenga una ruta válida
foreach ($users as &$user) {
    $user['avatar'] = $user['avatar'] ?: '../assets/img/default-avatar.png';
}

echo json_encode($users);
