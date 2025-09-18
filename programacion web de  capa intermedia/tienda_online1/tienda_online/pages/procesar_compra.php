<?php
session_start();
include '../includes/db.php';

header('Content-Type: application/json');

// Configuración para debug
ini_set('display_errors', 1);
error_log("Procesando compra de PayPal - " . date('Y-m-d H:i:s'));

// Validar datos recibidos
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Log para debug
error_log("Datos recibidos en procesar_compra.php: " . $input);

// Validación mejorada
if (empty($input)) {
    error_log("Error: No se recibieron datos");
    echo json_encode(['success' => false, 'message' => 'No se recibieron datos']);
    exit;
}

if (json_last_error() !== JSON_ERROR_NONE) {
    error_log("Error JSON: " . json_last_error_msg());
    echo json_encode(['success' => false, 'message' => 'Error al decodificar JSON: ' . json_last_error_msg()]);
    exit;
}

if (!isset($data['transaction_id'], $data['payer_email'], $data['amount'], $data['usuario_id'], $data['carrito'])) {
    $missing = [];
    if (!isset($data['transaction_id'])) $missing[] = 'transaction_id';
    if (!isset($data['payer_email'])) $missing[] = 'payer_email';
    if (!isset($data['amount'])) $missing[] = 'amount';
    if (!isset($data['usuario_id'])) $missing[] = 'usuario_id';
    if (!isset($data['carrito'])) $missing[] = 'carrito';

    error_log("Error: Datos incompletos - Faltan: " . implode(', ', $missing));
    echo json_encode([
        'success' => false,
        'message' => 'Datos incompletos',
        'missing' => $missing,
        'received' => array_keys($data)
    ]);
    exit;
}

// Validar tipo de datos
if (!is_numeric($data['amount']) || floatval($data['amount']) <= 0) {
    error_log("Error: Monto inválido - " . $data['amount']);
    echo json_encode(['success' => false, 'message' => 'Monto inválido']);
    exit;
}

if (!is_array($data['carrito']) || empty($data['carrito'])) {
    error_log("Error: Carrito inválido o vacío");
    echo json_encode(['success' => false, 'message' => 'Carrito inválido o vacío']);
    exit;
}

try {
    error_log("Iniciando transacción en la base de datos");
    $conn->beginTransaction();

    // Guardar transaction_id de PayPal
    $paypal_transaction_id = $data['transaction_id'];

    // 1. Registrar en tabla compras
    $stmt = $conn->prepare("
        INSERT INTO compras (usuario_id, fecha_compra, total) 
        VALUES (?, NOW(), ?)
    ");
    $stmt->execute([$data['usuario_id'], floatval($data['amount'])]);
    $compra_id = $conn->lastInsertId();
    error_log("Compra registrada con ID: " . $compra_id);

    // 2. Registrar productos en compra_productos
    $productos_registrados = 0;
    foreach ($data['carrito'] as $producto_id => $cantidad) {
        // Verificar que el producto existe
        $stmt = $conn->prepare("SELECT id, precio FROM productos WHERE id = ?");
        $stmt->execute([$producto_id]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$producto) {
            throw new Exception("Producto no encontrado: ID $producto_id");
        }

        $stmt = $conn->prepare("
            INSERT INTO compra_productos (compra_id, producto_id, cantidad, precio) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$compra_id, $producto_id, $cantidad, $producto['precio']]);
        $productos_registrados++;
    }
    error_log("Productos registrados: " . $productos_registrados);

    // 3. Registrar en transacciones (ahora incluye transaction_id de PayPal)
    $stmt = $conn->prepare("
        INSERT INTO transacciones (compra_id, transaction_id, metodo_pago, estado, fecha_transaccion) 
        VALUES (?, ?, 'paypal', 'completado', NOW())
    ");
    $stmt->execute([$compra_id, $paypal_transaction_id]);
    $transaccion_id = $conn->lastInsertId();
    error_log("Transacción registrada con ID: " . $transaccion_id);

    $conn->commit();
    error_log("Transacción de base de datos completada con éxito");

    // Limpiar carrito
    unset($_SESSION['carrito']);

    echo json_encode([
        'success' => true,
        'message' => 'Compra procesada correctamente',
        'transaction_id' => $paypal_transaction_id,
        'compra_id' => $compra_id,
        'transaccion_id' => $transaccion_id
    ]);
} catch (Exception $e) {
    $conn->rollBack();
    error_log("Error en procesar_compra.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error en la base de datos: ' . $e->getMessage()
    ]);
}
