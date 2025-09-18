<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: /pages/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $lista_id = intval($_POST['lista_id']);
    $estado_actual = intval($_POST['estado_actual']);

    // Invertir el estado
    $nuevo_estado = $estado_actual ? 0 : 1;

    // Validar que la lista pertenezca al usuario
    $stmt = $conn->prepare("SELECT id FROM listas WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$lista_id, $usuario_id]);
    if ($stmt->fetch()) {
        // Actualizar el estado
        $update = $conn->prepare("UPDATE listas SET es_publica = ? WHERE id = ?");
        $update->execute([$nuevo_estado, $lista_id]);
    }
}

header("Location: perfil.php");
exit;
