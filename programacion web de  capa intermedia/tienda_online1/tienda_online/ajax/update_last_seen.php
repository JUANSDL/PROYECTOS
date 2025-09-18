<?php
session_start();
include '../../includes/db.php';

$usuario_id = $_SESSION['usuario_id'];
$stmt = $conn->prepare("UPDATE usuarios SET last_seen = NOW() WHERE id = ?");
$stmt->execute([$usuario_id]);
