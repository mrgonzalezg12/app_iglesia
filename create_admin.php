<?php
require_once 'config.php';

$nombre = "Admin";
$email = "admin@example.com";
$password = "admin";
$rol = "admin";

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
if ($stmt === false) {
    die("Error al preparar la consulta: " . $conn->error);
}

$stmt->bind_param("ssss", $nombre, $email, $hash, $rol);
if (!$stmt->execute()) {
    die("Error al ejecutar la consulta: " . $stmt->error);
}

echo "Usuario administrador creado correctamente.";

$stmt->close();
$conn->close();

