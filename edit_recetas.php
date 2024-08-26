<?php
require 'vendor/autoload.php'; // Asegúrate de que la ubicación del archivo autoload.php sea correcta

use Firebase\JWT\JWT;

// Inicia la sesión para acceder a los datos de la misma
session_start();

// Verifica el método HTTP
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    include_once 'leer_configuracion.php';

    // Obtener el ID de la receta desde la URL
    if (isset($_GET['id_receta'])) {
        $id_receta = intval($_GET['id_receta']); // Asegúrate de convertir a entero
    } else {
        echo json_encode(["success" => false, "message" => "ID de receta no proporcionado."]);
        exit();
    }

    // Obtener los datos enviados en el cuerpo de la solicitud PUT
    $data = json_decode(file_get_contents("php://input"), true);

    // Obtener los datos de la receta a editar
    $titulo = $data['titulo'] ?? null;
    $descripcion = $data['descripcion'] ?? null;
    $id_tipo_receta = $data['id_tipo_receta'] ?? null;
    $id_usuario = $data['id_usuario'] ?? null;

    // Verificar que los datos necesarios estén presentes
    if (is_null($titulo) || is_null($descripcion) || is_null($id_tipo_receta)) {
        echo json_encode(["success" => false, "message" => "Faltan datos para actualizar la receta."]);
        exit();
    }

    // // Obtener el ID del usuario desde la sesión
    // if (isset($_SESSION['usuario_id'])) {
    //     $id_usuario = $_SESSION['usuario_id'];
    // } else {
    //     echo json_encode(["success" => false, "message" => "Usuario no autenticado."]);
    //     exit();
    // }

    try {
        // Consulta SQL para actualizar la receta
        $stmt = $conexion->prepare("UPDATE recetas SET titulo = ?, descripcion = ?, id_tipo_receta = ? WHERE id_receta = ? AND id_usuario = ?");
        $stmt->bind_param("ssiis", $titulo, $descripcion, $id_tipo_receta, $id_receta, $id_usuario);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            http_response_code(201);

            echo json_encode(["success" => true, "message" => "Receta actualizada con éxito."]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al actualizar la receta."]);
        }

        // Cerrar la declaración
        $stmt->close();

    } catch (Exception $ex) {
        http_response_code(500);

        $error_message = "Ocurrió una excepción al intentar actualizar la receta: " . $ex->getMessage();
        Logger::logError($error_message, $log_file);
        echo json_encode(["success" => false, "message" => $error_message]);
    } finally {
        // Cierra la conexión a la base de datos
        $conexion->close();
    }
} else {
    http_response_code(404);
    echo json_encode(["error" => "Método no permitido."]);
}
?>