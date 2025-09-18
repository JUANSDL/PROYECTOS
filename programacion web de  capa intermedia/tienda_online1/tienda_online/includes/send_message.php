<?php
require_once '../includes/db.php';
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'No autenticado']);
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$chat_id = $_POST['chat_id'] ?? 0;
$message = trim($_POST['message'] ?? '');
// AÑADIR ESTA LÍNEA PARA CAPTURAR EL ID_Producto
$id_producto = $_POST['id_producto'] ?? NULL; // Puede ser NULL si el mensaje no está asociado a un producto específico

if (empty($message)) {
    echo json_encode(['success' => false, 'message' => 'El mensaje no puede estar vacío']);
    exit();
}

try {
    // Verificar que el usuario pertenece al chat
    $check_query = "SELECT 1 FROM participantes_chat WHERE ID_Chat = :chat_id AND ID_Usuario = :user_id";
    $stmt = $conn->prepare($check_query);
    $stmt->execute([':chat_id' => $chat_id, ':user_id' => $usuario_id]);

    if ($stmt->rowCount() === 0) {
        echo json_encode(['success' => false, 'message' => 'No tienes acceso a este chat']);
        exit();
    }

    // Insertar mensaje
    // MODIFICAR ESTA LÍNEA PARA INCLUIR ID_Producto
    $insert_query = "INSERT INTO mensajes (ID_Chat, ID_Usuario, Texto, ID_Producto) VALUES (:chat_id, :user_id, :message, :id_producto)";
    $stmt = $conn->prepare($insert_query);
    $stmt->execute([
        ':chat_id' => $chat_id,
        ':user_id' => $usuario_id,
        ':message' => $message,
        ':id_producto' => $id_producto // AÑADIR ESTA LÍNEA
    ]);

    // Actualizar FechaUltimoMensaje en el chat
    $update_query = "UPDATE chats SET FechaUltimoMensaje = NOW() WHERE ID_Chat = :chat_id";
    $stmt = $conn->prepare($update_query);
    $stmt->execute([':chat_id' => $chat_id]);

    echo json_encode(['success' => true, 'message' => 'Mensaje enviado']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error al enviar el mensaje: ' . $e->getMessage()]);
}
