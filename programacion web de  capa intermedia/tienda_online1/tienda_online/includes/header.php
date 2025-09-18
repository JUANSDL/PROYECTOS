<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Online</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tu CSS personalizado -->
    <link rel="stylesheet" href="/tienda_online/assets/css/styles.css">
</head>

<body>
    <!-- Barra de navegación con Bootstrap -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/tienda_online/index.php">Tienda Online</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <!-- Enlaces comunes para todos los usuarios -->
                    <li class="nav-item">
                        <a class="nav-link" href="/tienda_online/index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/tienda_online/pages/productos.php">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/tienda_online/pages/carrito.php">Carrito</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <!-- Enlaces para usuarios autenticados -->
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <!-- Enlaces para superadministradores -->
                        <?php if ($_SESSION['rol'] === 'superadministrador'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/tienda_online/pages/crear_admin.php">Crear Administrador</a>
                            </li>
                        <?php endif; ?>

                        <!-- Enlaces para administradores -->
                        <?php if ($_SESSION['rol'] === 'administrador'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/tienda_online/pages/admin_productos.php">Administrar Productos</a>
                            </li>
                        <?php endif; ?>

                        <!-- Enlaces para vendedores -->
                        <?php if ($_SESSION['rol'] === 'vendedor'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/tienda_online/pages/agregar_producto.php">Agregar Producto</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/tienda_online/pages/agregar_categoria.php">Agregar Categoría</a>
                            </li>
                        <?php endif; ?>

                        <!-- Enlaces para todos los usuarios autenticados -->
                        <li class="nav-item">
                            <a class="nav-link" href="/tienda_online/pages/perfil.php">Perfil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/tienda_online/pages/chat.php">Chat</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/tienda_online/pages/logout.php">Cerrar Sesión</a>
                        </li>

                    <?php else: ?>
                        <!-- Enlaces para usuarios no autenticados -->
                        <li class="nav-item">
                            <a class="nav-link" href="/tienda_online/pages/login.php">Iniciar Sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/tienda_online/pages/registro.php">Registrarse</a>
                        </li>


                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>