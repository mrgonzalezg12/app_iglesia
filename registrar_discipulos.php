<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: index.php");
    exit;
}

$rol = $_SESSION['role'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $lider_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO discipulos (nombre, email, lider_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $nombre, $email, $lider_id);

    if ($stmt->execute()) {
        $success = "Discípulo registrado correctamente";
    } else {
        $error = "Error al registrar el discípulo: " . $stmt->error;
    }

    $stmt->close();
}

$discipulos = obtenerDiscipulos($rol, $_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar discípulos</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Registrar discípulos</h1>
        <?php if (isset($success)) { ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php } ?>
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Registrar discípulo</button>
        </form>
        <h2 class="mt-5">Mis discípulos</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo electrónico</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($discipulos as $discipulo) { ?>
                        <tr>
                            <td><?php echo $discipulo['nombre']; ?></td>
                            <td><?php echo $discipulo['email']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <a href="dashboard.php" class="btn btn-secondary btn-block mt-4">Volver al inicio</a>
    </div>
</body>
</html>

<?php
function obtenerDiscipulos($rol, $lider_id) {
    global $conn;
    $sql = "SELECT * FROM discipulos WHERE lider_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $lider_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}
?>

