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

$usuario_id = $_SESSION['usuario_id'];
$usuario_rol = $_SESSION['rol'] ?? 'cliente'; // Obtener el rol del usuario actual

// Obtener información del usuario actual
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);
$usuario_actual = $stmt->fetch();

// Define the formatLastMessageTime function
function formatLastMessageTime($timestamp)
{
    if (empty($timestamp)) return '';

    $now = new DateTime();
    $messageTime = new DateTime($timestamp);
    $diff = $now->diff($messageTime);

    if ($diff->days > 0) {
        return $diff->days == 1 ? 'Ayer' : date('d/m/Y', strtotime($timestamp));
    } elseif ($diff->h > 0) {
        return 'Hace ' . $diff->h . ' hora' . ($diff->h > 1 ? 's' : '');
    } elseif ($diff->i > 0) {
        return 'Hace ' . $diff->i . ' minuto' . ($diff->i > 1 ? 's' : '');
    } else {
        return 'Ahora mismo';
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat | Tienda Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .chat-container {
            display: flex;
            height: 80vh;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .contacts-list {
            width: 300px;
            border-right: 1px solid #ddd;
            overflow-y: auto;
        }

        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .messages-container {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            background-color: #f9f9f9;
        }

        .message-input {
            padding: 15px;
            border-top: 1px solid #ddd;
            background-color: white;
        }

        .contact {
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .contact:hover,
        .contact.active {
            background-color: #f0f0f0;
        }

        .message {
            margin-bottom: 10px;
            padding: 8px 12px;
            border-radius: 18px;
            max-width: 70%;
        }

        .sent {
            background-color: #007bff;
            color: white;
            margin-left: auto;
            border-bottom-right-radius: 0;
        }

        .received {
            background-color: #e9ecef;
            margin-right: auto;
            border-bottom-left-radius: 0;
        }

        .unread-count {
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
            margin-left: 5px;
        }

        .time {
            font-size: 11px;
            color: #999;
            margin-top: 3px;
        }

        .sent .time {
            color: #e1f5fe;
        }

        #search-results {
            position: absolute;
            width: 100%;
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            background: white;
            border: 1px solid #ddd;
            border-radius: 0 0 5px 5px;
            display: none;
        }

        .search-result-item {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }

        .search-result-item:hover {
            background-color: #f5f5f5;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }

        /* Estilos para el botón Ver Perfil */
        .view-profile-btn {
            margin-left: 10px;
            white-space: nowrap;
            padding: 3px 8px;
            font-size: 12px;
            border-radius: 15px;
        }

        .contact-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .contact-info {
            display: flex;
            align-items: center;
            flex-grow: 1;
            min-width: 0;
        }

        .contact-text {
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Estilos para la información del producto dentro del mensaje */
        .product-info {
            background-color: rgba(255, 255, 255, 0.8);
            /* Fondo claro para resaltar */
            padding: 5px 10px;
            border-radius: 10px;
            margin-bottom: 5px;
            color: #333;
            /* Color de texto para que contraste */
        }

        .sent .product-info {
            background-color: rgba(255, 255, 255, 0.9);
            color: #333;
        }

        .received .product-info {
            background-color: rgba(240, 240, 240, 0.9);
            color: #333;
        }

        .product-info img {
            border: 1px solid #eee;
        }
    </style>
</head>

<body>
    <div class="container my-4">
        <h2 class="mb-4">Chat</h2>

        <div class="mb-3 position-relative">
            <div class="input-group">
                <input type="text" id="user-search" class="form-control" placeholder="Buscar usuarios...">
                <button class="btn btn-primary" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <div id="search-results" class="mt-1"></div>
        </div>

        <div class="chat-container">
            <div class="contacts-list bg-white">
                <?php
                // Obtener chats del usuario
                $query = "SELECT c.ID_Chat, u.id as user_id, u.username, u.avatar, c.FechaUltimoMensaje,
                             (SELECT COUNT(*) FROM mensajes m WHERE m.ID_Chat = c.ID_Chat AND m.ID_Usuario != ? AND m.leido = FALSE) as unread_count
                             FROM chats c
                             JOIN participantes_chat pc ON c.ID_Chat = pc.ID_Chat
                             JOIN usuarios u ON pc.ID_Usuario = u.id
                             WHERE pc.ID_Usuario != ? AND c.ID_Chat IN (
                                 SELECT ID_Chat FROM participantes_chat WHERE ID_Usuario = ?
                             )
                             ORDER BY c.FechaUltimoMensaje DESC";

                $stmt = $conn->prepare($query);
                $stmt->execute([$usuario_id, $usuario_id, $usuario_id]);
                $chats = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($chats as $chat):
                    $active = isset($_GET['chat_id']) && $_GET['chat_id'] == $chat['ID_Chat'] ? 'active' : '';
                ?>
                    <div class="contact <?= $active ?>" data-chat-id="<?= $chat['ID_Chat'] ?>">
                        <div class="contact-content">
                            <div class="contact-info">
                                <img src="<?= htmlspecialchars($chat['avatar']) ?>" alt="Avatar" class="user-avatar">
                                <div class="contact-text">
                                    <strong><?= htmlspecialchars($chat['username']) ?></strong>
                                    <?php if ($chat['unread_count'] > 0): ?>
                                        <span class="unread-count"><?= $chat['unread_count'] ?></span>
                                    <?php endif; ?>
                                    <div class="time"><?= formatLastMessageTime($chat['FechaUltimoMensaje']) ?></div>
                                </div>
                            </div>
                            <button class="btn btn-sm btn-outline-primary view-profile-btn"
                                data-user-id="<?= $chat['user_id'] ?>">
                                <i class="fas fa-user"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="chat-area bg-white">
                <?php
                if (isset($_GET['chat_id'])):
                    $chat_id = $_GET['chat_id'];

                    // Verificar que el usuario tiene acceso a este chat
                    $check_query = "SELECT 1 FROM participantes_chat WHERE ID_Chat = ? AND ID_Usuario = ?";
                    $stmt = $conn->prepare($check_query);
                    $stmt->execute([$chat_id, $usuario_id]);
                    $has_access = $stmt->rowCount() > 0;

                    if ($has_access):
                        // Marcar mensajes como leídos
                        $update_query = "UPDATE mensajes SET leido = TRUE WHERE ID_Chat = ? AND ID_Usuario != ? AND leido = FALSE";
                        $stmt = $conn->prepare($update_query);
                        $stmt->execute([$chat_id, $usuario_id]);

                        // Obtener información del otro participante
                        $participant_query = "SELECT u.id, u.username, u.avatar FROM usuarios u
                                              JOIN participantes_chat pc ON u.id = pc.ID_Usuario
                                              WHERE pc.ID_Chat = ? AND pc.ID_Usuario != ?";
                        $stmt = $conn->prepare($participant_query);
                        $stmt->execute([$chat_id, $usuario_id]);
                        $participant = $stmt->fetch(PDO::FETCH_ASSOC);

                        // Obtener mensajes (MODIFICADO PARA INCLUIR PRODUCTO)
                        $messages_query = "SELECT m.*, u.username, u.avatar, p.nombre AS nombre_producto, p.imagenes AS imagen_producto
                                           FROM mensajes m
                                           JOIN usuarios u ON m.ID_Usuario = u.id
                                           LEFT JOIN productos p ON m.ID_Producto = p.id -- JOIN con productos
                                           WHERE m.ID_Chat = ?
                                           ORDER BY m.Fecha ASC";
                        $stmt = $conn->prepare($messages_query);
                        $stmt->execute([$chat_id]);
                        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                        <div class="chat-header p-3 border-bottom d-flex align-items-center">
                            <img src="<?= htmlspecialchars($participant['avatar']) ?>" alt="Avatar" class="user-avatar">
                            <h5 class="mb-0"><?= htmlspecialchars($participant['username']) ?></h5>
                            <button class="btn btn-sm btn-outline-primary ms-auto view-profile-btn"
                                data-user-id="<?= $participant['id'] ?>">
                                <i class="fas fa-user"></i> Ver Perfil
                            </button>
                        </div>

                        <div class="messages-container" id="messages-container">
                            <?php foreach ($messages as $message): ?>
                                <div class="message <?= $message['ID_Usuario'] == $usuario_id ? 'sent' : 'received' ?>">
                                    <?php if (!empty($message['nombre_producto'])):
                                        // Decodificar las imágenes si las tienes como JSON
                                        $imagenes_producto = json_decode($message['imagen_producto'], true);
                                        $primera_imagen = !empty($imagenes_producto) ? htmlspecialchars($imagenes_producto[0]) : '../assets/img/default-product.png';
                                    ?>
                                        <div class="product-info mb-2 d-flex align-items-center border-bottom pb-2">
                                            <img src="<?= $primera_imagen ?>" alt="Producto" class="img-fluid me-2" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                            <div>
                                                <strong>Producto:</strong> <a href="/pages/productos.php?id=<?= $message['ID_Producto'] ?>" target="_blank" class="text-decoration-none text-dark"><?= htmlspecialchars($message['nombre_producto']) ?></a>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($message['Texto']) ?>
                                    <div class="time text-end">
                                        <?= date('H:i', strtotime($message['Fecha'])) ?>
                                    </div>
                                    <?php
                                    // Mostrar botón "Establecer Precio Personalizado" solo para vendedores
                                    if (!empty($message['ID_Producto']) && $usuario_rol === 'vendedor'):
                                        // Asegurarse de que $participant['id'] y $participant['username'] estén disponibles
                                        // (ya se obtienen al principio de la sección del chat activo)
                                    ?>
                                        <button class="btn btn-sm btn-info mt-1"
                                            onclick="openSetPriceModal(
                                                    <?= htmlspecialchars($message['ID_Producto']) ?>,
                                                    '<?= htmlspecialchars($message['nombre_producto']) ?>',
                                                    <?= htmlspecialchars($participant['id']) ?>,
                                                    '<?= htmlspecialchars($participant['username']) ?>'
                                                )">
                                            Establecer Precio Personalizado
                                        </button>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="message-input">
                            <form id="message-form">
                                <input type="hidden" name="chat_id" value="<?= $chat_id ?>">
                                <div class="input-group">
                                    <input type="text" name="message" class="form-control" placeholder="Escribe un mensaje..." required>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    <?php
                    else:
                    ?>
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <div class="text-center">
                                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                <h4>No tienes acceso a este chat</h4>
                                <p>Por favor selecciona otro chat de la lista</p>
                            </div>
                        </div>
                    <?php
                    endif;
                else:
                    ?>
                    <div class="d-flex justify-content-center align-items-center h-100">
                        <div class="text-center">
                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                            <h4>Selecciona un chat</h4>
                            <p>Elige una conversación de la lista o busca un usuario para comenzar a chatear</p>
                        </div>
                    </div>
                <?php
                endif;
                ?>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <div class="modal fade" id="setPriceModal" tabindex="-1" aria-labelledby="setPriceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="setPriceModalLabel">Establecer Precio Personalizado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formSetPrice">
                    <div class="modal-body">
                        <input type="hidden" id="modalProductId" name="id_producto">
                        <input type="hidden" id="modalClientId" name="id_cliente">
                        <div class="mb-3">
                            <label for="modalProductName" class="form-label">Producto:</label>
                            <input type="text" class="form-control" id="modalProductName" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="modalClientName" class="form-label">Cliente:</label>
                            <input type="text" class="form-control" id="modalClientName" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="customPrice" class="form-label">Precio Personalizado:</label>
                            <input type="number" class="form-control" id="customPrice" name="precio_personalizado" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Precio</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Autocompletado de búsqueda de usuarios
            $('#user-search').on('input', function() {
                const query = $(this).val().trim();

                if (query.length < 2) {
                    $('#search-results').hide().empty();
                    return;
                }

                $.ajax({
                    url: '../includes/search_users.php',
                    method: 'POST',
                    data: {
                        query: query
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.length > 0) {
                            $('#search-results').empty();
                            data.forEach(function(user) {
                                if (user.id != <?= $usuario_id ?>) {
                                    $('#search-results').append(`
                                    <div class="search-result-item" data-user-id="${user.id}">
                                        <div class="d-flex align-items-center">
                                            <img src="${user.avatar}" alt="Avatar" class="user-avatar">
                                            <div>
                                                <strong>${user.username}</strong>
                                                <div class="text-muted small">${user.nombre_completo || ''}</div>
                                            </div>
                                        </div>
                                    </div>
                                `);
                                }
                            });
                            $('#search-results').show();
                        } else {
                            $('#search-results').hide().empty();
                        }
                    }
                });
            });

            // Selección de usuario para iniciar chat
            $(document).on('click', '.search-result-item', function() {
                const userId = $(this).data('user-id');

                $.ajax({
                    url: '../includes/start_chat.php',
                    method: 'POST',
                    data: {
                        user_id: userId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'chat.php?chat_id=' + response.chat_id;
                        } else {
                            alert('Error al iniciar el chat: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('Error al comunicarse con el servidor');
                    }
                });
            });

            // Cerrar resultados de búsqueda al hacer clic fuera
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#user-search, #search-results').length) {
                    $('#search-results').hide();
                }
            });

            // Selección de chat existente
            $('.contact').click(function(e) {
                // Verificar si el clic fue en el botón de perfil o en alguno de sus elementos hijos
                if ($(e.target).closest('.view-profile-btn').length === 0) {
                    const chatId = $(this).data('chat-id');
                    window.location.href = 'chat.php?chat_id=' + chatId;
                }
            });

            // Envío de mensaje
            $('#message-form').submit(function(e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    url: '../includes/send_message.php',
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#message-form input[name="message"]').val('');
                            // Recargar la página para mostrar el nuevo mensaje
                            location.reload();
                        } else {
                            alert('Error al enviar el mensaje: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('Error al comunicarse con el servidor');
                    }
                });
            });

            //boton ver perfil
            $(document).on('click', '.view-profile-btn', function(e) {
                e.stopPropagation();
                const userId = $(this).data('user-id');
                window.location.href = '/pages/perfil.php?id=' + userId;
            });

            // Auto scroll al fondo del chat
            const messagesContainer = $('#messages-container');
            if (messagesContainer.length) {
                messagesContainer.scrollTop(messagesContainer[0].scrollHeight);
            }

            // =======================================================
            // LÓGICA PARA EL MODAL DE PRECIO PERSONALIZADO
            // =======================================================

            // Función para abrir el modal de precio personalizado
            window.openSetPriceModal = function(productId, productName, clientId, clientName) {
                $('#modalProductId').val(productId);
                $('#modalProductName').val(productName);
                $('#modalClientId').val(clientId);
                $('#modalClientName').val(clientName);
                $('#customPrice').val(''); // Limpiar el campo de precio por si acaso

                // Opcional: Cargar precio personalizado existente (requeriría una llamada AJAX)
                // Si quieres que el campo 'customPrice' se precargue con el precio ya establecido,
                // aquí harías otra llamada AJAX a un endpoint que te devuelva el precio_personalizado
                // para ese producto y cliente.

                var setPriceModal = new bootstrap.Modal(document.getElementById('setPriceModal'));
                setPriceModal.show();
            };

            // Envío del formulario de precio personalizado
            $('#formSetPrice').submit(function(e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    url: '../includes/procesar_precio_personalizado.php',
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            var setPriceModal = bootstrap.Modal.getInstance(document.getElementById('setPriceModal'));
                            setPriceModal.hide();
                            // Puedes recargar el chat para que el vendedor vea que la operación fue exitosa,
                            // o simplemente cerrar el modal. Depende de la UX deseada.
                            // location.reload();
                        } else {
                            alert('Error al guardar el precio: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error AJAX: ", status, error, xhr.responseText);
                        alert('Error al comunicarse con el servidor al guardar el precio.');
                    }
                });
            });
        });
    </script>
</body>

</html>