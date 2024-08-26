// Después de haber creado con éxito, establece el código de respuesta a 201
http_response_code(201);

// Puedes enviar una respuesta adicional si es necesario
echo json_encode(array('mensaje' => 'Recurso creado con éxito'));