<?php
include '../includes/header.php'; // Aquí ya se llama a session_start()
include '../includes/db.php';

// Verificar si el usuario es superadministrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'superadministrador') {
    header("Location: /tienda_online/pages/login.php");
    exit;
}

// Procesar el formulario de creación de administradores
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $nombre_completo = $_POST['nombre_completo'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $sexo = $_POST['sexo'];

    // Insertar el nuevo administrador
    $stmt = $conn->prepare("INSERT INTO usuarios (email, username, password, rol, nombre_completo, fecha_nacimiento, sexo, es_publico) VALUES (?, ?, ?, 'administrador', ?, ?, ?, TRUE)");
    $stmt->execute([$email, $username, $password, $nombre_completo, $fecha_nacimiento, $sexo]);

    echo "<div class='alert alert-success'>Administrador creado exitosamente.</div>";
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Crear Administrador</h2>
    <form method="POST" action="" class="needs-validation" novalidate>
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Nombre de Usuario:</label>
            <input type="text" class="form-control" id="username" name="username" required minlength="3">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="mb-3">
            <label for="nombre_completo" class="form-label">Nombre Completo:</label>
            <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" required>
        </div>

        <div class="mb-3">
            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento:</label>
            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required max="<?php echo date('Y-m-d'); ?>">
        </div>

        <div class="mb-3">
            <label for="sexo" class="form-label">Sexo:</label>
            <select class="form-select" id="sexo" name="sexo">
                <option value="masculino">Masculino</option>
                <option value="femenino">Femenino</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Crear Administrador</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>