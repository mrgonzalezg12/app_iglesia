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
    $usuarios = $_POST['usuarios'];

    foreach ($usuarios as $usuario_id) {
        $stmt = $conn->prepare("INSERT INTO asistencia (reunion_id, usuario_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $reunion_id, $usuario_id);
        $stmt->execute();
    }

    $success = "Asistencia registrada correctamente";
}

$reuniones = obtenerReuniones($rol);
$usuarios = obtenerUsuarios($rol);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar asistencia</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Registrar asistencia</h1>
        <?php if (isset($success)) { ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php } ?>
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
            <div class="form-group">
                <label for="usuarios">Usuarios</label>
                <select class="form-control" id="usuarios" name="usuarios[]" multiple>
                    <option value="">Selecciona los usuarios</option>
                    <?php foreach ($usuarios as $usuario) { ?>
                        <option value="<?php echo $usuario['id']; ?>"><?php echo $usuario['nombre']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Registrar asistencia</button>
        </form>
    </div>
</body>
</html>

<?php
function obtenerReuniones($rol) {
    global $conn;
    $sql = "SELECT * FROM reuniones";
    if ($rol !== 'admin') {
        $sql .= " WHERE lider_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_SESSION['user_id']);
    } else {
        $stmt = $conn->prepare($sql);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function obtenerUsuarios($rol) {
    global $conn;
    $sql = "SELECT * FROM usuarios WHERE rol = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $rol);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

