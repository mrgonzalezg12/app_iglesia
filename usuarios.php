<?php
session_start();
require_once 'config.php';

// Verificar si el usuario tiene permisos de administrador
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

// Procesar las acciones del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];

    // Eliminar un usuario
    if ($accion === 'eliminar') {
        $usuario_id = $_POST['usuario_id'];
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $usuario_id);
        if ($stmt->execute()) {
            $success = "Usuario eliminado correctamente";
        } else {
            $error = "Error al eliminar el usuario: " . $stmt->error;
        }
        $stmt->close();
    }
    // Resetear la contraseña de un usuario
    elseif ($accion === 'resetear_clave') {
        $usuario_id = $_POST['usuario_id'];
        $nueva_clave = "nuevaclave";
        $hash = password_hash($nueva_clave, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hash, $usuario_id);
        if ($stmt->execute()) {
            $success = "Contraseña del usuario reseteada a 'nuevaclave'";
        } else {
            $error = "Error al resetear la contraseña del usuario: " . $stmt->error;
        }
        $stmt->close();
    }
    // Crear un nuevo usuario
    elseif ($accion === 'crear_usuario') {
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $rol = $_POST['rol'];

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nombre, $email, $hash, $rol);

        if ($stmt->execute()) {
            $success = "Usuario creado correctamente";
        } else {
            $error = "Error al crear el usuario: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Obtener la lista de usuarios
$usuarios = obtenerUsuarios();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de usuarios</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Administración de usuarios</h1>

        <!-- Mostrar mensajes de éxito o error -->
        <?php if (isset($success)) { ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php } ?>
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>

        <!-- Tabla de usuarios -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo electrónico</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario) { ?>
                        <tr>
                            <td><?php echo $usuario['nombre']; ?></td>
                            <td><?php echo $usuario['email']; ?></td>
                            <td><?php echo $usuario['rol']; ?></td>
                            <td>
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                                    <input type="hidden" name="usuario_id" value="<?php echo $usuario['id']; ?>">
                                    <button type="submit" name="accion" value="eliminar" class="btn btn-danger btn-sm">Eliminar</button>
                                    <button type="submit" name="accion" value="resetear_clave" class="btn btn-warning btn-sm">Resetear clave</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Formulario para crear un nuevo usuario -->
        <h2 class="mt-5">Crear nuevo usuario</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="rol">Rol</label>
                <select class="form-control" id="rol" name="rol" required>
                    <option value="pastor">Pastor</option>
                    <option value="lider_12">Líder de 12</option>
                    <option value="lider_144">Líder de 144</option>
                    <option value="lider_1728">Líder de 1728</option>
                </select>
            </div>
            <input type="hidden" name="accion" value="crear_usuario">
            <button type="submit" class="btn btn-primary btn-block">Crear usuario</button>
        </form>
        <a href="dashboard.php" class="btn btn-secondary btn-block mt-4">Volver al inicio</a>
    </div>
</body>
</html>

<?php
function obtenerUsuarios() {
    global $conn;
    $sql = "SELECT * FROM usuarios";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}
?>

