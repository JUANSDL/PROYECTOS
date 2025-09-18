<?php
session_start();

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_producto'])) {
    $id = intval($_POST['id_producto']);

    if ($id <= 0) {
        echo json_encode(['exito' => false, 'mensaje' => 'ID inválido']);
        exit;
    }

    // Aumentar cantidad si ya existe, o establecer en 1 si es nuevo
    if (isset($_SESSION['carrito'][$id])) {
        $_SESSION['carrito'][$id]++;
    } else {
        $_SESSION['carrito'][$id] = 1;
    }

    echo json_encode(['exito' => true]);
} else {
    echo json_encode(['exito' => false, 'mensaje' => 'Petición no válida']);
}
