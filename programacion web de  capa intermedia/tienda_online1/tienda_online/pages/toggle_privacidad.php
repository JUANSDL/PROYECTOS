<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: /pages/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lista_id'])) {
    $lista_id = $_POST['lista_id'];
    $usuario_id = $_SESSION['usuario_id'];

    // Verificar que la lista pertenece al usuario actual
    $stmt = $conn->prepare("SELECT es_publica FROM listas WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$lista_id, $usuario_id]);
    $lista = $stmt->fetch();

    if ($lista) {
        // Cambiar el valor de es_publica (si es 1, poner 0; si es 0, poner 1)
        $nuevo_estado = $lista['es_publica'] ? 0 : 1;

        $stmt = $conn->prepare("UPDATE listas SET es_publica = ? WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$nuevo_estado, $lista_id, $usuario_id]);
    }
}

// Redirigir de vuelta al perfil
header("Location: perfil.php");
exit;
