<?php
include '../includes/db.php';
include '../includes/header.php';

// Verificar si el usuario es un vendedor
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'vendedor') {
    header("Location: /tienda_online/pages/login.php");
    exit;
}

// Procesar el formulario de agregar categoría
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $usuario_id = $_SESSION['usuario_id'];

    // Insertar la categoría
    $stmt = $conn->prepare("INSERT INTO categorias (nombre, descripcion, usuario_id) VALUES (?, ?, ?)");
    $stmt->execute([$nombre, $descripcion, $usuario_id]);

    $success = "Categoría agregada exitosamente.";
}
?>

<!-- Contenido principal -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mb-4">Agregar Categoría</h2>

            <!-- Mostrar mensaje de éxito -->
            <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <!-- Formulario de agregar categoría -->
            <form method="POST" action="" class="needs-validation" novalidate>
                <!-- Nombre de la Categoría -->
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre de la Categoría:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>

                <!-- Descripción -->
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción:</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                </div>

                <!-- Botón de Agregar Categoría -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Agregar Categoría</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>