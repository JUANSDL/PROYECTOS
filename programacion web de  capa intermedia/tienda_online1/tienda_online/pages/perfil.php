<?php
include '../includes/db.php';
include '../includes/header.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: /pages/login.php");
    exit;
}

// Obtener el ID del usuario a mostrar (si viene por GET) o el del usuario logueado
$perfil_usuario_id = isset($_GET['id']) ? $_GET['id'] : $_SESSION['usuario_id'];
$es_propio_perfil = ($perfil_usuario_id == $_SESSION['usuario_id']);

// Obtener información del usuario cuyo perfil se está viendo
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$perfil_usuario_id]);
$usuario = $stmt->fetch();

if (!$usuario) {
    header("Location: /pages/error.php?mensaje=Usuario no encontrado");
    exit;
}

// Obtener las listas del usuario (solo públicas si no es el propio perfil)
if ($es_propio_perfil) {
    $stmt = $conn->prepare("SELECT * FROM listas WHERE usuario_id = ?");
    $stmt->execute([$perfil_usuario_id]);
} else {
    $stmt = $conn->prepare("SELECT * FROM listas WHERE usuario_id = ? AND es_publica = TRUE");
    $stmt->execute([$perfil_usuario_id]);
}
$listas = $stmt->fetchAll();

$listas_con_productos = [];
foreach ($listas as $lista) {
    $stmt = $conn->prepare("SELECT p.* FROM productos p INNER JOIN lista_productos lp ON lp.producto_id = p.id WHERE lp.lista_id = ?");
    $stmt->execute([$lista['id']]);
    $productos = $stmt->fetchAll();

    $listas_con_productos[] = [
        'lista' => $lista,
        'productos' => $productos
    ];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de <?= htmlspecialchars($usuario['username']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        .avatar {
            width: 150px;
            height: 150px;
            object-fit: cover;
        }

        .lista-productos {
            max-height: 300px;
            overflow-y: auto;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Perfil de <?= htmlspecialchars($usuario['username']) ?></h2>
                <?php if ($es_propio_perfil): ?>
                    <div class="alert alert-info">
                        Estás viendo tu propio perfil
                    </div>
                <?php endif; ?>

                <div class="avatar-container text-center">
                    <?php if ($usuario['avatar']): ?>
                        <img src="<?= htmlspecialchars($usuario['avatar']) ?>" alt="Avatar" class="avatar img-fluid rounded-circle">
                    <?php else: ?>
                        <p class="text-muted">No hay avatar disponible.</p>
                    <?php endif; ?>
                </div>
                <div class="mt-4">
                    <p><strong>Nombre de Usuario:</strong> <?= htmlspecialchars($usuario['username']) ?></p>
                    <?php if ($es_propio_perfil): ?>
                        <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
                    <?php endif; ?>
                    <p><strong>Rol:</strong> <?= htmlspecialchars($usuario['rol']) ?></p>
                </div>
            </div>
        </div>

        <!-- Listas del usuario -->
        <div class="card mt-4">
            <div class="card-body">
                <h3 class="card-title"><?= $es_propio_perfil ? 'Mis Listas' : 'Listas Públicas' ?></h3>
                <?php if (count($listas_con_productos) > 0): ?>
                    <ul class="list-group">
                        <?php foreach ($listas_con_productos as $item): ?>
                            <?php $lista = $item['lista']; ?>
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4><?= htmlspecialchars($lista['nombre']) ?></h4>
                                        <p><?= htmlspecialchars($lista['descripcion']) ?></p>
                                        <p><strong>Privacidad:</strong> <?= $lista['es_publica'] ? 'Pública' : 'Privada' ?></p>
                                        <?php if ($es_propio_perfil): ?>
                                            <form action="toggle_privacidad.php" method="post" class="d-inline">
                                                <input type="hidden" name="lista_id" value="<?= $lista['id'] ?>">
                                                <button type="submit" class="btn btn-secondary btn-sm">
                                                    <?= $lista['es_publica'] ? 'Hacer Privada' : 'Hacer Pública' ?>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Productos -->
                                <?php if (count($item['productos']) > 0): ?>
                                    <div class="row mt-3 lista-productos">
                                        <?php foreach ($item['productos'] as $producto): ?>
                                            <?php $imagenes = json_decode($producto['imagenes'], true); ?>
                                            <div class="col-md-3 mb-3">
                                                <div class="card h-100">
                                                    <img src="<?= htmlspecialchars($imagenes[0] ?? 'https://via.placeholder.com/150') ?>" class="card-img-top" style="height: 120px; object-fit: cover;">
                                                    <div class="card-body">
                                                        <h6><?= htmlspecialchars($producto['nombre']) ?></h6>
                                                        <p><strong>$<?= number_format($producto['precio'], 2) ?></strong></p>
                                                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#productModal<?= $producto['id'] ?>">Ver</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal para detalles del producto -->
                                            <div class="modal fade" id="productModal<?= $producto['id'] ?>" tabindex="-1" aria-labelledby="productModalLabel<?= $producto['id'] ?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="productModalLabel<?= $producto['id'] ?>"><?= htmlspecialchars($producto['nombre']) ?></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <?php if (!empty($imagenes)): ?>
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
                                                            <p><strong>Categoría:</strong> <?= htmlspecialchars($producto['categoria'] ?? 'Sin categoría') ?></p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                            <button class="btn btn-success btn-add-cart" data-id="<?= $producto['id'] ?>">
                                                                🛒 Añadir al carrito
                                                            </button>
                                                            <?php if ($es_propio_perfil): ?>
                                                                <form method="post" action="eliminar_de_lista.php" class="d-inline">
                                                                    <input type="hidden" name="lista_id" value="<?= $lista['id'] ?>">
                                                                    <input type="hidden" name="producto_id" value="<?= $producto['id'] ?>">
                                                                    <button type="submit" class="btn btn-outline-danger btn-sm">❌ Eliminar de Lista</button>
                                                                </form>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted">Esta lista no tiene productos aún.</p>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted"><?= $es_propio_perfil ? 'No tienes listas creadas.' : 'Este usuario no tiene listas públicas.' ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script para añadir al carrito
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
                    .then(response => {
                        alert('Producto añadido al carrito');
                    })
                    .catch(error => {
                        console.error('Error al añadir:', error);
                    });
            });
        });
    </script>
</body>

</html>