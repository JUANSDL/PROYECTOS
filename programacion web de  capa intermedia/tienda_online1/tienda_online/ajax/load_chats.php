<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo '<p class="text-danger">Debes iniciar sesión para ver los chats.</p>';
    exit;
}

echo '<p class="text-muted">No tienes conversaciones aún.</p>';
