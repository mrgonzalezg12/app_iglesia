<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

$rol = $_SESSION['role'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $fecha = $_POST['fecha'];
    $lider_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO reuniones (nombre, fecha, lider_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $nombre, $fecha, $lider_id);

    if ($stmt->execute()) {
        $success = "Reunión registrada correctamente";
    } else {
        $error = "Error al registrar la reunión: " . $stmt->error;
    }

    $stmt->close();
}

$reuniones = obtenerReuniones($rol, $_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar reunión</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Registrar reunión</h1>
        <?php if (isset($success)) { ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php } ?>
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="form-group">
                <label for="nombre">Nombre de la reunión</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="fecha">Fecha de la reunión</label>
                <input type="datetime-local" class="form-control" id="fecha" name

