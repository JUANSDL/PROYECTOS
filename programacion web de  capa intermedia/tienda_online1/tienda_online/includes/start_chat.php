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
$other_user_id = $_POST['user_id'] ?? 0;
$producto_id = $_POST['producto_id'] ?? null; // Nuevo parámetro para el producto

// Validar IDs
if (empty($other_user_id)) {
    echo json_encode(['success' => false, 'message' => 'ID de usuario inválido']);
    exit();
}

try {
    // Verificar si ya existe un chat entre estos dos usuarios sobre este producto
    $check_query = "SELECT c.ID_Chat FROM chats c
                   JOIN participantes_chat pc1 ON c.ID_Chat = pc1.ID_Chat
                   JOIN participantes_chat pc2 ON c.ID_Chat = pc2.ID_Chat
                   WHERE pc1.ID_Usuario = :user_id 
                   AND pc2.ID_Usuario = :other_user_id
                   AND c.ID_Producto " . ($producto_id ? "= :producto_id" : "IS NULL");

    $stmt = $conn->prepare($check_query);
    $stmt->bindParam(':user_id', $usuario_id);
    $stmt->bindParam(':other_user_id', $other_user_id);

    if ($producto_id) {
        $stmt->bindParam(':producto_id', $producto_id);
    }

    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $chat = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode([
            'success' => true,
            'chat_id' => $chat['ID_Chat'],
            'exists' => true
        ]);
        exit();
    }

    // Crear nuevo chat
    $conn->beginTransaction();

    // Insertar nuevo chat (con referencia al producto si existe)
    $insert_chat = "INSERT INTO chats (FechaUltimoMensaje, ID_Producto) VALUES (NOW(), :producto_id)";
    $stmt = $conn->prepare($insert_chat);

    if ($producto_id) {
        $stmt->bindParam(':producto_id', $producto_id);
    } else {
        $stmt->bindValue(':producto_id', null, PDO::PARAM_NULL);
    }

    $stmt->execute();
    $chat_id = $conn->lastInsertId();

    // Insertar participantes
    $insert_participant = "INSERT INTO participantes_chat (ID_Chat, ID_Usuario) VALUES (:chat_id, :user_id)";
    $stmt = $conn->prepare($insert_participant);

    // Insertar usuario actual
    $stmt->execute([':chat_id' => $chat_id, ':user_id' => $usuario_id]);

    // Insertar otro usuario
    $stmt->execute([':chat_id' => $chat_id, ':user_id' => $other_user_id]);

    $conn->commit();

    echo json_encode([
        'success' => true,
        'chat_id' => $chat_id,
        'exists' => false
    ]);
} catch (PDOException $e) {
    $conn->rollBack();
    echo json_encode([
        'success' => false,
        'message' => 'Error al crear el chat: ' . $e->getMessage()
    ]);
}
