<?php

    require 'vendor/autoload.php';

    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;
    //comprobatr si existe la cookie con el token
    // if(isset($_COOKIE['jwt'])){

        $key = "messi_mejor_que_maradona"; // Reemplaza con tu clave secreta
        $token = [
            "iss" => "http://localhost", // Emisor
            "aud" => "http://localhost", // Audiencia
            "iat" => time(), // Fecha de emisión
            "nbf" => time(), // No válido antes de
            "exp" => time() + 3600 // Expiración (1 hora desde la emisión)
        ]; // Puedes ajustar esto según la forma en que envíes el token
        if($token){
            $token_decodificado = verifyJWT($token,$key);
            if($token_decodificado){
                echo "Acceso a la seccion de productos permitido";
            }else{
                // No se proporcionó un token en la solicitud
                header("Location: index.php"); // Redirigir a la página de bienvenida
            }
        }
    // }else{
    //     // No se proporcionó un token en la solicitud
    //     header("Location: index.php"); // Redirigir a la página de bienvenida
    // }

    // Función para verificar y decodificar el token JWT
    function verifyJWT($token, $key)
    {
       

        try {
            // Decodificar el token JWT utilizando la clave secreta
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            return $decoded;
        } catch (Exception $e) {
            // Manejar errores de decodificación o expiración del token
            return null;
        }
    }



?>