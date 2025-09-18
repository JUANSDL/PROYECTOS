<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Obtener conteo de mensajes no leídos por chat
$stmt = $conn->prepare("
    SELECT c.id AS chat_id, COUNT(m.id) AS unread_count 
    FROM chats c
    LEFT JOIN mensajes m ON m.chat_id = c.id AND m.usuario_id != ? AND m.leido = 0
    WHERE (c.usuario1_id = ? OR c.usuario2_id = ?)
    GROUP BY c.id
");
$stmt->execute([$usuario_id, $usuario_id, $usuario_id]);
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($resultados);
