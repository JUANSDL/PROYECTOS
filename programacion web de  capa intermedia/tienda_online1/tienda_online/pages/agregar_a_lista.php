<?php
session_start();
include(__DIR__ . '/../includes/db.php');

$usuario_id = $_SESSION['usuario_id'] ?? null;

if (!$usuario_id) {
    die('Acceso denegado. Debes iniciar sesión.');
}

$id_producto = $_POST['id_producto'] ?? null;
$lista_existente = $_POST['lista_existente'] ?? '';
$nombre_lista = trim($_POST['nombre_lista'] ?? '');
$es_publica = isset($_POST['es_publica']) ? 1 : 0;

try {
    $conn->beginTransaction();

    // Crear nueva lista si no se seleccionó una existente
    if (empty($lista_existente) && !empty($nombre_lista)) {
        $stmt = $conn->prepare("INSERT INTO listas (usuario_id, nombre, es_publica) VALUES (?, ?, ?)");
        $stmt->execute([$usuario_id, $nombre_lista, $es_publica]);
        $lista_id = $conn->lastInsertId();
    } else {
        $lista_id = $lista_existente;
    }

    // Insertar producto en la lista si no existe ya
    if ($lista_id && $id_producto) {
        // Verificar si ya existe
        $stmt = $conn->prepare("SELECT COUNT(*) FROM lista_productos WHERE lista_id = ? AND producto_id = ?");
        $stmt->execute([$lista_id, $id_producto]);
        $existe = $stmt->fetchColumn();

        if (!$existe) {
            $stmt = $conn->prepare("INSERT INTO lista_productos (lista_id, producto_id) VALUES (?, ?)");
            $stmt->execute([$lista_id, $id_producto]);
        }
    }

    $conn->commit();
    header('Location: productos.php?mensaje=Producto agregado a la lista');
    exit;
} catch (Exception $e) {
    $conn->rollBack();
    echo "Error al agregar a la lista: " . $e->getMessage();
}
