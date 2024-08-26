<?php

    class Logger {
        private static $log_file;

        public static function setLogFile($file){
            self::$log_file = $file;
        }

        //Esta funcion permite imprimir texto en el archivo log
        public static function logError($message) {
            if (isset(self::$log_file)) {
                $timestamp = date("Y-m-d H:i:s");
                //Esta linea imprime el mensaje en el 
                //archivo con el nombre que proporcionemos:
                file_put_contents(self::$log_file, 
                                    "[$timestamp] $message" . PHP_EOL, FILE_APPEND);
            }else {
                error_log("Error: Archivo de registro no configurado.");
            }
        }




    }


?>