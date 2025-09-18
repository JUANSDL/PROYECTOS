<?php
session_start();
include '../../includes/db.php';

$usuario_id = $_SESSION['usuario_id'];
$id_chat = $_POST['id_chat'] ?? null;

if (!$id_chat) exit;

// Obtener todos los mensajes no leídos por el usuario
$stmt = $conn->prepare("SELECT m.id FROM mensajes m
                        LEFT JOIN mensajes_leidos ml ON m.id = ml.id_mensaje AND ml.id_usuario = ?
                        WHERE m.id_chat = ? AND ml.id_mensaje IS NULL");
$stmt->execute([$usuario_id, $id_chat]);
$mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($mensajes as $m) {
    $conn->prepare("INSERT INTO mensajes_leidos (id_mensaje, id_usuario) VALUES (?, ?)")
        ->execute([$m['id'], $usuario_id]);
}
