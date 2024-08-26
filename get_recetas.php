<?php
require 'vendor/autoload.php'; // Asegúrate de que la ubicación del archivo autoload.php sea correcta

use Firebase\JWT\JWT;

// Inicia la sesión para acceder a los datos de la misma
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    include_once 'leer_configuracion.php';

    // Obtener el ID del usuario desde la sesión
    if (isset($_SESSION['id_usuario'])) {
        $id_usuario = $_SESSION['id_usuario'];
    } else {
        // Si no hay usuario en la sesión, redirigir a login
        header("Location: login.php");
        exit();
    }

    try {
        // Consulta SQL para obtener todas las recetas
        $stmt = $conexion->prepare("SELECT * FROM recetas WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);

        // Ejecutar la consulta
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si hay recetas
        if ($result->num_rows > 0) {
            $recetas = $result->fetch_all(MYSQLI_ASSOC);
            // Respuesta exitosa con código 200
            http_response_code(200);
            echo json_encode(["success" => true, "recetas" => $recetas]);
        } else {
            // Respuesta con código 404 si no hay recetas
            http_response_code(404);
            echo json_encode(["success" => false, "message" => "No se encontraron recetas."]);
        }

        // Cerrar la declaración
        $stmt->close();

    } catch (Exception $ex) {
        $error_message = "Ocurrió una excepción al intentar obtener las recetas: " . $ex->getMessage();
        Logger::logError($error_message, $log_file);
        // Respuesta de error con código 500
        http_response_code(500);
        echo json_encode(["success" => false, "message" => $error_message]);
    } finally {
        // Cierra la conexión a la base de datos
        $conexion->close();
    }
} else {
    // Respuesta de método no permitido con código 405
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido."]);
}