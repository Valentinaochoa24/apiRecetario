<?php
require 'vendor/autoload.php'; // Asegúrate de que la ubicación del archivo autoload.php sea correcta

use Firebase\JWT\JWT;

// Inicia la sesión para acceder a los datos de la misma
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include_once 'leer_configuracion.php';

    // Obtener los datos enviados desde el formulario
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $id_tipo_receta = $_POST['id_tipo_receta'];
    $id_usuario = $_POST['id_usuario'];

    if (is_null($titulo) || is_null($descripcion) || is_null($id_tipo_receta) || is_null($id_usuario)) {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Faltan datos para actualizar la receta."]);
        exit();
    }

    try {
        // Consulta SQL para insertar una nueva receta
        $stmt = $conexion->prepare("INSERT INTO recetas (titulo, descripcion, id_tipo_receta, id_usuario) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $titulo, $descripcion, $id_tipo_receta, $id_usuario);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Respuesta exitosa con código 201
            http_response_code(201);
            echo json_encode(["success" => true, "message" => "Receta guardada con éxito."]);
        } else {
            // Respuesta de error con código 500
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Error al guardar la receta."]);
        }

        // Cerrar la declaración
        $stmt->close();

    } catch (Exception $ex) {
        $error_message = "Ocurrió una excepción al intentar guardar la receta: " . $ex->getMessage();
        Logger::logError($error_message, $log_file);
        // Respuesta de error con código 500
        http_response_code(500);
        echo json_encode(["success" => false, "message" => $error_message]);
    } finally {
        // Cierra la conexión a la base de datos
        $conexion->close();
    }
} else {
    // Respuesta de método no permitido con código 404
    http_response_code(404);
    echo json_encode(["error" => "Método no permitido."]);
}