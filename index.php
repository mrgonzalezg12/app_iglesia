<?php
// Iniciar sesión
session_start();

// Incluir el archivo de configuración
require_once 'config.php';

// Verificar si el usuario ya ha iniciado sesión
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    // Redirigir al usuario al dashboard
    header("Location: dashboard.php");
    exit;
}

// Procesar el formulario de inicio de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Obtener el usuario de la base de datos
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nombre = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    // Verificar las credenciales del usuario
    if ($usuario && password_verify($password, $usuario['password'])) {
        // Iniciar sesión y redirigir al usuario al dashboard
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['role'] = $usuario['rol'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Credenciales inválidas";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Inicio de sesión</h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="form-group">
                        <label for="username">Nombre de usuario</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <?php if (isset($error)) { ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php } ?>
                    <button type="submit" class="btn btn-primary btn-block">Iniciar sesión</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

