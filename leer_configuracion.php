<?php 
    // Ruta al archivo JSON que contiene las credenciales
    $credenciales_file = 'database.json';
    $log_file = 'error.log';
    //Importamos el código que permite escribir en el log
    include_once 'write_log.php';
    Logger::setLogFile($log_file);
    // Verifica si el archivo existe y es legible
    if (file_exists($credenciales_file) && is_readable($credenciales_file)) {
        // Lee el contenido del archivo JSON
        $credenciales = json_decode(file_get_contents($credenciales_file), true);

        if ($credenciales !== null) {
            // Comprueba si todas las claves necesarias están presentes en el 
            //archivo JSON
            $required_keys = ['host', 'username', 'password', 'database'];
            
            if (count(array_diff($required_keys, array_keys($credenciales))) === 0) {
                // Las credenciales están completas y son seguras para su uso
                $host = $credenciales['host'];
                $username = $credenciales['username'];
                $password = $credenciales['password'];
                $database = $credenciales['database'];

                try{
                    // Intenta realizar la conexión 
                    // Con la base de datos
                    $conexion = new mysqli($host, $username, $password, $database);
                }catch(Exception $ex){
                    //Captura la excepción y la transmite en un archivo de errores LOG.
                    $error_message = "Error de conexión: " . $ex->getMessage();
                    Logger::logError($error_message, $log_file);
                    // Redirigir a la página de bienvenida
                    header("Location: server_error_500.html"); 
                }
                

                // Verifica la conexión
                if ($conexion->connect_error) {
                    $error_message = "No se pudo establecer conexión al servidor: ";
                    Logger::logError($error_message, $log_file);
                    header("Location: server_error_500.html"); // Redirigir a la página de bienvenida
                }
            } else {
                header("Location: server_error_500.html"); // Redirigir a la página de bienvenida
            }
        } else {
            header("Location: server_error_500.html"); // Redirigir a la página de bienvenida
        }
    } else {
        header("Location: server_error_500.html"); // Redirigir a la página de bienvenida
    }
?>
