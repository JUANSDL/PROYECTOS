<?php
include '../includes/header.php'; // Aquí ya se llama a session_start()
include '../includes/db.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: /tienda_online/pages/login.php");
    exit;
}

// Aprobar el producto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto_id = $_POST['producto_id'];
    $stmt = $conn->prepare("UPDATE productos SET autorizado = TRUE WHERE id = ?");
    $stmt->execute([$producto_id]);
}

header("Location: /tienda_online/pages/admin_productos.php");
exit;
