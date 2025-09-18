<?php
include '../includes/header.php';
include '../includes/db.php';

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Eliminar producto del carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    $idProducto = intval($_POST['id_producto']);
    unset($_SESSION['carrito'][$idProducto]);
}

// Actualizar cantidad
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
    $idProducto = intval($_POST['id_producto']);
    $nuevaCantidad = max(1, intval($_POST['cantidad']));
    $_SESSION['carrito'][$idProducto] = $nuevaCantidad;
}

// Obtener productos
$productosCarrito = [];
$total = 0;

if (!empty($_SESSION['carrito'])) {
    $ids = array_keys($_SESSION['carrito']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $conn->prepare("SELECT * FROM productos WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $productos = $stmt->fetchAll();

    foreach ($productos as $producto) {
        $id = $producto['id'];
        $cantidad = $_SESSION['carrito'][$id];
        $producto['cantidad'] = $cantidad;
        $producto['subtotal'] = $cantidad * $producto['precio'];
        $total += $producto['subtotal'];
        $productosCarrito[] = $producto;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body>

    <div class="container mt-5">
        <h1 class="text-center mb-4">🛒 Carrito de Compras</h1>

        <?php if (empty($productosCarrito)): ?>
            <p class="text-center">Tu carrito está vacío. <a href="productos.php">Explora productos</a></p>
        <?php else: ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Imagen</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productosCarrito as $producto): ?>
                        <tr>
                            <td><?= htmlspecialchars($producto['nombre']) ?></td>
                            <td>
                                <img src="<?= htmlspecialchars(json_decode($producto['imagenes'])[0] ?? 'https://via.placeholder.com/50') ?>" width="50">
                            </td>
                            <td>$<?= number_format($producto['precio'], 2) ?></td>
                            <td>
                                <form method="post" class="d-inline">
                                    <input type="hidden" name="id_producto" value="<?= $producto['id'] ?>">
                                    <input type="number" name="cantidad" value="<?= $producto['cantidad'] ?>" min="1" class="form-control d-inline w-50">
                                    <button type="submit" name="actualizar" class="btn btn-sm btn-warning">Actualizar</button>
                                </form>
                            </td>
                            <td>$<?= number_format($producto['subtotal'], 2) ?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="id_producto" value="<?= $producto['id'] ?>">
                                    <button type="submit" name="eliminar" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3 class="text-end">Total: $<?= number_format($total, 2) ?></h3>
            <div class="text-end">
                <a href="productos.php" class="btn btn-secondary">Seguir Comprando</a>
                <a href="checkout.php" class="btn btn-success">Finalizar Compra</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>