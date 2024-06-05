<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

$rol = $_SESSION['role'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reunion_id = $_POST['reunion_id'];

    $asistencia = obtenerAsistencia($reunion_id);
}

$reuniones = obtenerReuniones($rol, $_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de asistencia</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Reporte de asistencia</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="form-group">
                <label for="reunion">Reunión</label>
                <select class="form-control" id="reunion" name="reunion_id">
                    <option value="">Selecciona una reunión</option>
                    <?php foreach ($reuniones as $reunion) { ?>
                        <option value="<?php echo $reunion['id']; ?>"><?php echo $reunion['nombre']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Ver reporte</button>
        </form>
        <?php if (isset($asistencia)) { ?>
            <h2 class="mt-5">Asistencia a la reunión</h2>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo electrónico</th>
                            <th>Asistió</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($asistencia as $registro) { ?>
                            <tr>
                                <td><?php echo $registro['nombre']; ?></td>
                                <td><?php echo $registro['email']; ?></td>
                                <td><?php echo $registro['asistio'] ? 'Sí' : 'No'; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </div>
</body>
</html>

<?php
function obtenerReuniones($rol, $lider_id) {
    global $conn;
    $sql = "SELECT * FROM reuniones WHERE lider_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $lider_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function obtenerAsistencia($reunion_id) {
    global $conn;
    $sql = "
        SELECT u.nombre, u.email, a.asistio
        FROM asistencia a
        JOIN usuarios u ON a.usuario_id = u.id
        WHERE a.reunion_id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reunion_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC