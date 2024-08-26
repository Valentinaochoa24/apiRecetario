<?php
session_start();
try {
    include_once 'leer_configuracion.php';

    // Si se envió el formulario de inicio de sesión o registro
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user = $_POST['username'];
        $correo = $_POST['correo'];
        $telefono = $_POST['telefono'];
        $pass = $_POST['password'];

        // Preparar y ejecutar la consulta para obtener el usuario
        $stmt = $conexion->prepare("SELECT * FROM usuario WHERE nombre = ? LIMIT 1");
        $stmt->bind_param('s', $user);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();

        if ($usuario) {
            // Si el usuario existe, verificar la contraseña
            if (password_verify($pass, $usuario['password'])) {
                $_SESSION['id_usuario'] = $usuario['id'];
                $_SESSION['usuario'] = $user;
                http_response_code(201);
                header("Location: bienvenido.php");
                exit();
            } else {
                http_response_code(404);
                $error = "Usuario o contraseña incorrectos.";
            }
        } else {
            // Si el usuario no existe, registrar uno nuevo
            $hashed_password = password_hash($pass, PASSWORD_BCRYPT);
            $stmt = $conexion->prepare("INSERT INTO usuario (nombre, correo, telefono, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('ssss', $user, $correo, $telefono, $hashed_password);

            if ($stmt->execute()) {
                $id_usuario = $conexion->insert_id; // Obtiene el ID del último registro insertado
                $_SESSION['id_usuario'] = $id_usuario;
                $_SESSION['usuario'] = $user;
                http_response_code(201);
                header("Location: bienvenido.php");
                exit();
            } else {
                http_response_code(404);

                $error = "Error al registrar el usuario.";
            }
        }
    }
} catch (Exception $e) {
    http_response_code(500);

    die("Error en la conexión: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Iniciar Sesión</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        <form method="post" action="login.php">
                            <div class="mb-3">
                                <label for="username" class="form-label">Nombre de usuario</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="correo" class="form-label">correo de usuario</label>
                                <input type="text" class="form-control" id="correo" name="correo" required>
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label">telefono de usuario</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
