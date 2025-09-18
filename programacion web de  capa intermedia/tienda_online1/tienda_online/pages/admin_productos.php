<?php
include '../includes/header.php'; // Aquí ya se llama a session_start()
include '../includes/db.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: /tienda_online/pages/login.php");
    exit;
}

// Obtener productos pendientes de aprobación
$stmt = $conn->prepare("SELECT * FROM productos WHERE autorizado = FALSE");
$stmt->execute();
$productos = $stmt->fetchAll();
?>

<h2>Administración de Productos</h2>
<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($productos as $producto): ?>
            <tr>
                <td><?php echo $producto['nombre']; ?></td>
                <td><?php echo $producto['descripcion']; ?></td>
                <td>$<?php echo $producto['precio']; ?></td>
                <td>
                    <form method="POST" action="aprobar_producto.php" style="display:inline;">
                        <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                        <button type="submit">Aprobar</button>
                    </form>
                    <form method="POST" action="rechazar_producto.php" style="display:inline;">
                        <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                        <button type="submit">Rechazar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>