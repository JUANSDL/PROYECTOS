<?php
include '../includes/header.php'; // Aquí ya se llama a session_start()
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->execute([$username]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($password, $usuario['password'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['rol'] = $usuario['rol'];
        header("Location: /tienda_online/index.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>

<!-- Contenido principal -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mb-4">Iniciar Sesión</h2>

            <!-- Mostrar errores -->
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <!-- Formulario de inicio de sesión -->
            <form method="POST" action="" class="needs-validation" novalidate>
                <!-- Nombre de Usuario -->
                <div class="mb-3">
                    <label for="username" class="form-label">Nombre de Usuario:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>

                <!-- Contraseña -->
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <!-- Botón de Iniciar Sesión -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                </div>
            </form>

            <!-- Enlace de registro -->
            <div class="mt-3 text-center">
                <p>¿No tienes una cuenta? <a href="/tienda_online/pages/registro.php">Regístrate aquí</a>.</p>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>