<?php
include '../includes/db.php';
include '../includes/header.php';

// Procesar el formulario de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $rol = trim($_POST['rol']);
    $nombre_completo = trim($_POST['nombre_completo']);
    $fecha_nacimiento = trim($_POST['fecha_nacimiento']);
    $sexo = trim($_POST['sexo']);
    $es_publico = isset($_POST['es_publico']) ? 1 : 0; // 1 para público, 0 para privado
    $avatar = $_FILES['avatar']; // Archivo de avatar

    // Validaciones del servidor
    $errors = [];

    // Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "El email no es válido.";
    }

    // Validar nombre de usuario
    if (strlen($username) < 3) {
        $errors[] = "El nombre de usuario debe tener al menos 3 caracteres.";
    }

    // Validar contraseña
    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        $errors[] = "La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.";
    }

    // Validar fecha de nacimiento (no puede ser en el futuro)
    $fecha_actual = new DateTime();
    $fecha_nacimiento_date = new DateTime($fecha_nacimiento);
    if ($fecha_nacimiento_date > $fecha_actual) {
        $errors[] = "La fecha de nacimiento no puede ser en el futuro.";
    }

    // Validar avatar
    if ($avatar['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024; // 2 MB

        if (!in_array($avatar['type'], $allowed_types)) {
            $errors[] = "El archivo debe ser una imagen (JPEG, PNG o GIF).";
        }

        if ($avatar['size'] > $max_size) {
            $errors[] = "El archivo no puede ser mayor a 2 MB.";
        }
    } else {
        $errors[] = "Debes subir una foto de avatar.";
    }

    // Verificar si el email o el nombre de usuario ya existen
    try {
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);
        if ($stmt->fetch()) {
            $errors[] = "El email o el nombre de usuario ya están registrados.";
        }
    } catch (PDOException $e) {
        $errors[] = "Error al verificar el email o el nombre de usuario.";
    }

    // Si no hay errores, registrar al usuario
    if (empty($errors)) {
        try {
            // Subir la imagen de avatar
            $avatar_name = uniqid() . '_' . basename($avatar['name']); // Nombre único para evitar colisiones
            $avatar_path = '../assets/images/avatars/' . $avatar_name;

            if (move_uploaded_file($avatar['tmp_name'], $avatar_path)) {
                // Hash de la contraseña
                $password_hash = password_hash($password, PASSWORD_BCRYPT);

                // Insertar usuario en la base de datos
                $stmt = $conn->prepare("INSERT INTO usuarios (email, username, password, rol, nombre_completo, fecha_nacimiento, sexo, es_publico, avatar) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$email, $username, $password_hash, $rol, $nombre_completo, $fecha_nacimiento, $sexo, $es_publico, $avatar_path]);

                // Redirigir al usuario a la página de inicio de sesión
                header('Location: login.php');
                exit;
            } else {
                $errors[] = "Error al subir la imagen de avatar.";
            }
        } catch (PDOException $e) {
            $errors[] = "Error al registrar el usuario. Por favor, inténtelo de nuevo.";
        }
    }
}
?>

<!-- Contenido principal -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="text-center mb-4">Registro de Usuario</h2>
            <form id="registroForm" method="POST" action="" enctype="multipart/form-data" class="needs-validation" novalidate>
                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                </div>

                <!-- Nombre de Usuario -->
                <div class="mb-3">
                    <label for="username" class="form-label">Nombre de Usuario:</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required minlength="3">
                </div>

                <!-- Contraseña -->
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <small class="form-text text-muted">La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.</small>
                </div>

                <!-- Rol -->
                <div class="mb-3">
                    <label for="rol" class="form-label">Rol:</label>
                    <select class="form-select" id="rol" name="rol" required>
                        <option value="cliente" <?php echo (isset($_POST['rol']) && $_POST['rol'] === 'cliente') ? 'selected' : ''; ?>>Cliente</option>
                        <option value="vendedor" <?php echo (isset($_POST['rol']) && $_POST['rol'] === 'vendedor') ? 'selected' : ''; ?>>Vendedor</option>
                    </select>
                </div>

                <!-- Nombre Completo -->
                <div class="mb-3">
                    <label for="nombre_completo" class="form-label">Nombre Completo:</label>
                    <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" value="<?php echo htmlspecialchars($_POST['nombre_completo'] ?? ''); ?>" required>
                </div>

                <!-- Fecha de Nacimiento -->
                <div class="mb-3">
                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento:</label>
                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($_POST['fecha_nacimiento'] ?? ''); ?>" required max="<?php echo date('Y-m-d'); ?>">
                </div>

                <!-- Sexo -->
                <div class="mb-3">
                    <label for="sexo" class="form-label">Sexo:</label>
                    <select class="form-select" id="sexo" name="sexo" required>
                        <option value="masculino" <?php echo (isset($_POST['sexo']) && $_POST['sexo'] === 'masculino') ? 'selected' : ''; ?>>Masculino</option>
                        <option value="femenino" <?php echo (isset($_POST['sexo']) && $_POST['sexo'] === 'femenino') ? 'selected' : ''; ?>>Femenino</option>
                    </select>
                </div>

                <!-- Perfil Público -->
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="es_publico" name="es_publico" <?php echo (isset($_POST['es_publico']) && $_POST['es_publico']) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="es_publico">Perfil Público</label>
                </div>

                <!-- Foto de Avatar -->
                <div class="mb-3">
                    <label for="avatar" class="form-label">Foto de Avatar:</label>
                    <input type="file" class="form-control" id="avatar" name="avatar" accept="image/jpeg, image/png, image/gif" required>
                </div>

                <!-- Botón de Registro -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Registrarse</button>
                </div>
            </form>

            <!-- Mostrar errores -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger mt-4">
                    <?php foreach ($errors as $error): ?>
                        <p class="mb-0"><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>