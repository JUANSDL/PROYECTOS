<?php
require_once '../includes/db.php';

$id_usuario1 = $_SESSION['id'];
$id_usuario2 = intval($_POST['id_usuario2']);

$sql = "
    SELECT c.ID_Chat 
    FROM chats c
    JOIN participantes_chat pc1 ON pc1.ID_Chat = c.ID_Chat AND pc1.ID_Usuario = ?
    JOIN participantes_chat pc2 ON pc2.ID_Chat = c.ID_Chat AND pc2.ID_Usuario = ?
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id_usuario1, $id_usuario2]);

if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'id_chat' => $row['ID_Chat']]);
} else {
    try {
        $pdo->beginTransaction();

        // Crear chat
        $pdo->exec("INSERT INTO chats (FechaUltimoMensaje) VALUES (NOW())");
        $id_chat = $pdo->lastInsertId();

        // Agregar participantes
        $stmt = $pdo->prepare("INSERT INTO participantes_chat (ID_Chat, ID_Usuario) VALUES (?, ?)");
        $stmt->execute([$id_chat, $id_usuario1]);
        $stmt->execute([$id_chat, $id_usuario2]);

        $pdo->commit();
        echo json_encode(['success' => true, 'id_chat' => $id_chat]);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
