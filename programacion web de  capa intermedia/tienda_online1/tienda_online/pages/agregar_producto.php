<?php
include '../includes/header.php';
include '../includes/db.php';

// Verificar si el usuario es un vendedor
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'vendedor') {
    header("Location: /tienda_online/pages/login.php");
    exit;
}

// Obtener categorías disponibles
$stmt = $conn->query("SELECT * FROM categorias");
$categorias = $stmt->fetchAll();

// Procesar el formulario de agregar producto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $cotizar = isset($_POST['cotizar']) ? 1 : 0;
    $precio = isset($_POST['precio']) ? $_POST['precio'] : null; // evita el warning
    $cantidad = $_POST['cantidad'];
    $categoria_id = $_POST['categoria_id'];
    $vendedor_id = $_SESSION['usuario_id'];

    if ($cotizar) {
        $precio = null; // o puedes usar 0 si lo prefieres
    }

    // Procesar las imágenes
    $imagenes = [];
    if (!empty($_FILES['imagenes']['name'][0])) {
        $total_imagenes = count($_FILES['imagenes']['name']);
        if ($total_imagenes < 3) {
            $error = "Debes subir al menos 3 imágenes.";
        } else {
            // Crear la carpeta de imágenes si no existe
            $carpeta_imagenes = '../assets/images/productos/';
            if (!is_dir($carpeta_imagenes)) {
                mkdir($carpeta_imagenes, 0777, true);
            }

            // Guardar las imágenes
            for ($i = 0; $i < $total_imagenes; $i++) {
                $nombre_archivo = uniqid() . '_' . basename($_FILES['imagenes']['name'][$i]);
                $ruta_temporal = $_FILES['imagenes']['tmp_name'][$i];
                $ruta_destino = $carpeta_imagenes . $nombre_archivo;

                // Validar el tipo de archivo (PNG o JPG)
                $tipo_archivo = $_FILES['imagenes']['type'][$i];
                $allowed_types = ['image/png', 'image/jpeg']; // Permitir PNG y JPG
                if (!in_array($tipo_archivo, $allowed_types)) {
                    $error = "La imagen " . $_FILES['imagenes']['name'][$i] . " no es un archivo PNG o JPG válido.";
                    continue; // Saltar esta imagen
                }

                // Validar el tamaño del archivo (máximo 2 MB)
                $tamano_archivo = $_FILES['imagenes']['size'][$i];
                if ($tamano_archivo > 2 * 1024 * 1024) { // 2 MB
                    $error = "La imagen " . $_FILES['imagenes']['name'][$i] . " es demasiado grande (máximo 2 MB).";
                    continue; // Saltar esta imagen
                }

                // Mover la imagen a la carpeta de destino
                if (move_uploaded_file($ruta_temporal, $ruta_destino)) {
                    $imagenes[] = $ruta_destino; // Guardar la ruta de la imagen
                } else {
                    $error = "Error al subir la imagen: " . $_FILES['imagenes']['name'][$i];
                }
            }

            // Verificar que se hayan subido al menos 3 imágenes válidas
            if (count($imagenes) < 3) {
                $error = "Debes subir al menos 3 imágenes válidas (PNG o JPG y máximo 2 MB cada una).";
            } else {
                // Procesar el video
                $video = null;
                if (!empty($_FILES['video']['name'])) {
                    // Crear la carpeta de videos si no existe
                    $carpeta_videos = '../assets/videos/productos/';
                    if (!is_dir($carpeta_videos)) {
                        mkdir($carpeta_videos, 0777, true);
                    }

                    // Validar el tipo de archivo (MP4)
                    $tipo_video = $_FILES['video']['type'];
                    if ($tipo_video !== 'video/mp4') {
                        $error = "El video debe ser un archivo MP4.";
                    } else {
                        // Validar el tamaño del video (máximo 50 MB)
                        $tamano_video = $_FILES['video']['size'];
                        if ($tamano_video > 50 * 1024 * 1024) { // 50 MB
                            $error = "El video es demasiado grande (máximo 50 MB).";
                        } else {
                            // Guardar el video
                            $nombre_video = uniqid() . '_' . basename($_FILES['video']['name']);
                            $ruta_temporal_video = $_FILES['video']['tmp_name'];
                            $ruta_destino_video = $carpeta_videos . $nombre_video;

                            if (move_uploaded_file($ruta_temporal_video, $ruta_destino_video)) {
                                $video = $ruta_destino_video; // Guardar la ruta del video
                            } else {
                                $error = "Error al subir el video.";
                            }
                        }
                    }
                }

                // Insertar el producto con las imágenes y el video
                $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, cantidad_disponible, categoria_id, vendedor_id, imagenes, video, solo_cotizacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$nombre, $descripcion, $precio, $cantidad, $categoria_id, $vendedor_id, json_encode($imagenes), $video, $cotizar]);


                $success = "Producto agregado exitosamente. Espera la aprobación de un administrador.";
            }
        }
    } else {
        $error = "Debes subir al menos 3 imágenes.";
    }
}
?>

<!-- Contenido principal -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="text-center mb-4">Agregar Producto</h2>

            <!-- Mostrar mensajes de éxito o error -->
            <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <!-- Formulario de agregar producto -->
            <form method="POST" action="" enctype="multipart/form-data" class="needs-validation" novalidate>
                <!-- Nombre del Producto -->
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Producto:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>

                <!-- Descripción -->
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción:</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                </div>

                <!-- Cotizar -->
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="cotizar" name="cotizar">
                    <label class="form-check-label" for="cotizar">Este producto es para cotizar</label>
                </div>

                <!-- Precio -->
                <div class="mb-3">
                    <label for="precio" class="form-label">Precio:</label>
                    <input type="number" class="form-control" id="precio" name="precio" step="0.01">
                </div>

                <!-- Cantidad Disponible -->
                <div class="mb-3">
                    <label for="cantidad" class="form-label">Cantidad Disponible:</label>
                    <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                </div>

                <!-- Categoría -->
                <div class="mb-3">
                    <label for="categoria_id" class="form-label">Categoría:</label>
                    <select class="form-select" id="categoria_id" name="categoria_id" required>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Imágenes -->
                <div class="mb-3">
                    <label for="imagenes" class="form-label">Imágenes (Mínimo 3, formato PNG o JPG, máximo 2 MB cada una):</label>
                    <input type="file" class="form-control" id="imagenes" name="imagenes[]" multiple accept="image/png, image/jpeg" required>
                </div>

                <!-- Video -->
                <div class="mb-3">
                    <label for="video" class="form-label">Video (Formato MP4, máximo 50 MB):</label>
                    <input type="file" class="form-control" id="video" name="video" accept="video/mp4">
                </div>

                <!-- Botón de Agregar Producto -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Agregar Producto</button>
                </div>
            </form>

            <!-- Script para desactivar el campo precio si se marca "cotizar" -->
            <script>
                document.getElementById('cotizar').addEventListener('change', function() {
                    const precioInput = document.getElementById('precio');
                    if (this.checked) {
                        precioInput.value = '';
                        precioInput.disabled = true;
                    } else {
                        precioInput.disabled = false;
                    }
                });
            </script>

            <?php include '../includes/footer.php'; ?>