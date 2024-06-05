<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: index.php");
    exit;
}

$rol = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de control</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 bg-dark text-white p-4">
                <h3>Menú</h3>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="dashboard.php">Inicio</a>
                    </li>
                    <?php if ($rol === 'admin') { ?>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="usuarios.php">Usuarios</a>
                        </li>
                    <?php } ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="registrar_reunion.php">Registrar reunión</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="registrar_asistencia.php">Registrar asistencia</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="reporte_asistencia.php">Reportes</a>
                    </li>
                    <?php if ($rol !== 'admin') { ?>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="registrar_discipulos.php">Registrar discípulos</a>
                        </li>
                    <?php } ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="logout.php">Cerrar sesión</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-9 p-4">
                <h1>Bienvenido, <?php echo $rol; ?></h1>
                <!-- Contenido específico del dashboard según el rol del usuario -->
            </div>
        </div>
    </div>
</body>
</html>

