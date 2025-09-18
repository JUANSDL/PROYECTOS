<?php
$host = 'localhost';
$dbname = 'tienda_online';
$username = 'root'; // Usuario por defecto de XAMPP
$password = '';     // Contraseña por defecto de XAMPP

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
