<?php
include '../includes/header.php';
include '../includes/db.php';

$usuario_id = $_SESSION['usuario_id'] ?? null;

// Búsqueda
$productos = [];

if (isset($_GET['buscar']) && trim($_GET['buscar']) !== '') {
    $busqueda = '%' . trim($_GET['buscar']) . '%';
    $stmt = $conn->prepare("SELECT p.*, u.id AS vendedor_id FROM productos p JOIN usuarios u ON p.vendedor_id = u.id WHERE p.autorizado = TRUE AND (p.nombre LIKE ? OR p.descripcion LIKE ?)");
    $stmt->execute([$busqueda, $busqueda]);
    $productos = $stmt->fetchAll();
} else {
    $stmt = $conn->query("SELECT p.*, u.id AS vendedor_id FROM productos p JOIN usuarios u ON p.vendedor_id = u.id WHERE p.autorizado = TRUE");
    $productos = $stmt->fetchAll();
}

// Listas del usuario
$listasUsuario = [];
if ($usuario_id) {
    $stmtListas = $conn->prepare("SELECT id, nombre FROM listas WHERE usuario_id = ?");
    $stmtListas->execute([$usuario_id]);
    $listasUsuario = $stmtListas->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>Productos Disponibles</h1>

        <!-- Formulario de búsqueda -->
        <form method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="buscar" class="form-control" placeholder="Buscar productos..." value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </div>
        </form>

        <!-- Resultados -->
        <div class="row">
            <?php if (empty($productos)): ?>
                <div class="col-12">
                    <p class="text-muted">No se encontraron productos.</p>
                </div>
            <?php else: ?>
                <?php foreach ($productos as $producto): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <?php
                            $imagenes = json_decode($producto['imagenes'], true);
                            $imagenPrincipal = $imagenes[0] ?? 'https://via.placeholder.com/150';
                            ?>
                            <img src="<?= htmlspecialchars($imagenPrincipal) ?>" class="card-img-top" alt="Imagen del producto">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($producto['nombre']) ?></h5>
                                <p><strong>Precio:</strong> $<?= number_format($producto['precio'], 2) ?></p>

                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#productModal<?= $producto['id'] ?>">
                                    Ver detalles
                                </button>

                                <?php if ($producto['solo_cotizacion']): ?>
                                    <a href="start_chat.php?vendedor_id=<?= $producto['vendedor_id'] ?>&producto_id=<?= $producto['id'] ?>" class="btn btn-outline-warning mt-2">
                                        ✉️ Enviar mensaje al vendedor
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-success btn-add-cart mt-2" data-id="<?= $producto['id'] ?>">
                                        🛒 Añadir al carrito
                                    </button>
                                <?php endif; ?>

                                <?php if ($usuario_id): ?>
                                    <button class="btn btn-outline-primary btn-sm mt-2 btn-agregar-lista"
                                        data-producto-id="<?= $producto['id'] ?>"
                                        data-bs-toggle="modal" data-bs-target="#modalAgregarLista">
                                        💖 Agregar a Lista
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modales para detalles de productos -->
    <?php foreach ($productos as $producto): ?>
        <div class="modal fade" id="productModal<?= $producto['id'] ?>" tabindex="-1" aria-labelledby="productModalLabel<?= $producto['id'] ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productModalLabel<?= $producto['id'] ?>"><?= htmlspecialchars($producto['nombre']) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php
                        $imagenes = json_decode($producto['imagenes'], true);
                        if (!empty($imagenes)):
                        ?>
                            <div class="mb-3">
                                <?php foreach ($imagenes as $imagen): ?>
                                    <img src="<?= htmlspecialchars($imagen) ?>" class="img-fluid mb-2" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($producto['video'])): ?>
                            <div class="mb-3">
                                <video controls class="w-100">
                                    <source src="<?= htmlspecialchars($producto['video']) ?>" type="video/mp4">
                                    Tu navegador no soporta el elemento de video.
                                </video>
                            </div>
                        <?php endif; ?>

                        <p><strong>Descripción:</strong> <?= htmlspecialchars($producto['descripcion']) ?></p>
                        <p><strong>Precio:</strong> $<?= number_format($producto['precio'], 2) ?></p>
                        <p><strong>Cantidad disponible:</strong> <?= htmlspecialchars($producto['cantidad_disponible']) ?></p>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

                            <?php if ($producto['solo_cotizacion']): ?>
                                <a href="mensaje.php?vendedor_id=<?= $producto['vendedor_id'] ?>&producto_id=<?= $producto['id'] ?>" class="btn btn-outline-warning">
                                    ✉️ Enviar mensaje al vendedor
                                </a>
                            <?php else: ?>
                                <button class="btn btn-success btn-add-cart" data-id="<?= $producto['id'] ?>">
                                    🛒 Añadir al carrito
                                </button>
                            <?php endif; ?>

                            <?php if ($usuario_id): ?>
                                <button class="btn btn-outline-primary btn-sm btn-agregar-lista"
                                    data-producto-id="<?= $producto['id'] ?>"
                                    data-bs-toggle="modal" data-bs-target="#modalAgregarLista">
                                    💖 Agregar a Lista
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Modal Global para Agregar a Lista -->
    <div class="modal fade" id="modalAgregarLista" tabindex="-1" aria-labelledby="modalAgregarListaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" action="/tienda_online/pages/agregar_a_lista.php">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAgregarListaLabel">Agregar a Lista</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_producto" id="modal-producto-id">

                        <div class="mb-3">
                            <label for="lista_existente" class="form-label">Seleccionar Lista</label>
                            <select class="form-select" name="lista_existente" id="lista_existente">
                                <option value="">-- Nueva Lista --</option>
                                <?php foreach ($listasUsuario as $lista): ?>
                                    <option value="<?= $lista['id'] ?>"><?= htmlspecialchars($lista['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="nombre_lista" class="form-label">Nombre Nueva Lista</label>
                            <input type="text" class="form-control" name="nombre_lista" id="nombre_lista">
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="es_publica" id="es_publica">
                            <label class="form-check-label" for="es_publica">Hacer lista pública</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.btn-add-cart').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                fetch('agregar_al_carrito.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `id_producto=${id}`
                    })
                    .then(res => res.text())
                    .then(response => alert('Producto añadido al carrito'))
                    .catch(error => console.error('Error al añadir:', error));
            });
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-agregar-lista')) {
                const productoId = e.target.getAttribute('data-producto-id');
                document.getElementById('modal-producto-id').value = productoId;
            }
        });
    </script>
</body>

</html>