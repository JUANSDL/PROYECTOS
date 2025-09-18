<?php
include '../includes/header.php';
include '../includes/db.php';

// Verificar si hay una transacción
if (!isset($_GET['transaction_id'])) {
    header('Location: carrito.php');
    exit();
}

// Obtener detalles de la compra
$transaction_id = $_GET['transaction_id'];

try {
    // Corregido: usamos 'nombre_completo' en vez de 'nombre'
    $stmt = $conn->prepare("
        SELECT c.*, t.*, t.transaction_id as paypal_transaction_id, u.nombre_completo as usuario_nombre 
        FROM compras c
        JOIN transacciones t ON c.id = t.compra_id
        JOIN usuarios u ON c.usuario_id = u.id
        WHERE t.id = ? OR t.transaction_id = ?
    ");
    $stmt->execute([$transaction_id, $transaction_id]);
    $compra = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$compra) {
        throw new Exception("Compra no encontrada con el ID de transacción: $transaction_id");
    }

    // Obtener productos de la compra
    $stmt = $conn->prepare("
        SELECT cp.*, p.nombre, p.imagenes 
        FROM compra_productos cp
        JOIN productos p ON cp.producto_id = p.id
        WHERE cp.compra_id = ?
    ");
    $stmt->execute([$compra['compra_id']]);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($productos)) {
        error_log("Advertencia: No se encontraron productos para la compra ID: " . $compra['compra_id']);
    }
} catch (Exception $e) {
    error_log("Error en compra-exitosa.php: " . $e->getMessage());
    die("<div class='alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>");
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra Exitosa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h1 class="card-title">¡Compra Exitosa!</h1>
            </div>
            <div class="card-body">
                <h3>Resumen de tu compra</h3>
                <p><strong>Cliente:</strong> <?= htmlspecialchars($compra['usuario_nombre']) ?></p>
                <p><strong>Número de compra:</strong> #<?= htmlspecialchars($compra['compra_id']) ?></p>
                <p><strong>ID de transacción PayPal:</strong> <?= htmlspecialchars($compra['paypal_transaction_id']) ?></p>
                <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($compra['fecha_compra'])) ?></p>
                <p><strong>Total:</strong> $<?= number_format($compra['total'], 2) ?></p>
                <p><strong>Método de pago:</strong> PayPal</p>
                <p><strong>Estado:</strong> <?= ucfirst(htmlspecialchars($compra['estado'])) ?></p>

                <h4 class="mt-4">Productos comprados:</h4>
                <?php if (!empty($productos)): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio unitario</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productos as $producto): ?>
                                <tr>
                                    <td>
                                        <?= htmlspecialchars($producto['nombre']) ?>
                                        <?php
                                        $imagenes = json_decode($producto['imagenes'], true);
                                        if (!empty($imagenes) && is_array($imagenes)): ?>
                                            <img src="<?= htmlspecialchars($imagenes[0]) ?>" width="50" class="ms-2" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                                        <?php endif; ?>
                                    </td>
                                    <td><?= intval($producto['cantidad']) ?></td>
                                    <td>$<?= number_format($producto['precio'], 2) ?></td>
                                    <td>$<?= number_format($producto['cantidad'] * $producto['precio'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total:</th>
                                <th>$<?= number_format($compra['total'], 2) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                <?php else: ?>
                    <div class="alert alert-warning">
                        No se encontraron detalles de los productos comprados.
                    </div>
                <?php endif; ?>

                <div class="text-center mt-4">
                    <a href="productos.php" class="btn btn-primary">Volver a la tienda</a>
                    <a href="mis-compras.php" class="btn btn-secondary">Ver mis compras</a>
                    <a href="javascript:window.print()" class="btn btn-info">Imprimir recibo</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Confirmar que la transacción se ha completado correctamente
        console.log("Compra procesada correctamente");
    </script>
</body>

</html>