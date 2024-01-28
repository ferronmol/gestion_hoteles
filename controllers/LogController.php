<?php
require_once __DIR__ . '/../db/DB.php';

class LogController
{
    public function logOut($username, $rol)
    {
        $logFile = __DIR__ . '/../logs/log.txt';

        // Obtener la fecha y hora actual
        $date = date('Y-m-d H:i:s');

        // Formato de registro: [Fecha y hora] - Usuario: [nombre de usuario] - Acción: Cierre de sesión
        $logMessage = "[" . $date . "] - Usuario: " . $username . " - Rol: " . $rol . " - Acción: Cierre de sesión" . PHP_EOL;

        //escribir en el archivo
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }

    public function logAccess($nombre, $rol)
    {


        $logFile = __DIR__ . '/../logs/log.txt';
        // Obtener la fecha y hora actual
        $date = date('Y-m-d H:i:s');

        $logMessage = "[" . $date . "] - Usuario: " . $nombre . " - Rol: " . $rol . " - Acción: Acceso a la zona privada" . PHP_EOL;

        //escribir en el archivo
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }

    // public function logMail(FormData $formData, $username, $rol)
    // {
    //     $logFile = '../logs/log.txt';

    //     // Obtener la fecha y hora actual
    //     $date = date('Y-m-d H:i:s');

    //     //Extraigo del objeto los datos que me interesan
    //     $name = $formData->getName();
    //     $email = $formData->getEmail();
    //     $tele = $formData->getTele();
    //     $dates = $formData->getDate();
    //     $message = $formData->getMessage();

    //     $logMessage = "[" . $date . "] - Usuario: " . $username . " - Rol: " . $rol . " - Acción: Envio de correo 
    //     de $name mail $email, tel $tele , en fecha $dates y mensaje: $message " . PHP_EOL;


    //     //escribir en el archivo
    //     file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);

    //     echo "Se ha enviado el correo.Log registrado en $logFile";
    // }

    //funcion para registrar intentos de acceso fallido
    public function logFailedAccess($username)
    {
        $logFile = __DIR__ . '/../logs/log.txt';

        // Obtener la fecha y hora actual
        $date = date('Y-m-d H:i:s');

        $logMessage = "[" . $date . "] - Usuario: " . $username . " - Acción: Acceso fallido a la zona privada" . PHP_EOL;

        //escribir en el archivo 
        try {
            //var_dump($logMessage);
            file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
        } catch (Exception $e) {
            echo "No se ha podido escribir en el archivo" . $e->getMessage();
        }
    }

    //funcion para registrar qeu el admin ha borrado, insertado o modificado un hotel
    public function logAdminAction($username, $rol, $accion)
    {
        $logFile = __DIR__ . '/../logs/log.txt';

        // Obtener la fecha y hora actual
        $date = date('Y-m-d H:i:s');

        $logMessage = "[" . $date . "] - Usuario: " . $username . " - Rol: " . $rol . " - Acción: " . $accion . PHP_EOL;

        //escribir en el archivo
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
        echo "Se ha registrado la acción del administrador";
    }
    public function logMod($accion)
    {
        $logFile = __DIR__ . '/../logs/log.txt';

        // Obtener la fecha y hora actual
        $date = date('Y-m-d H:i:s');

        $logMessage = "[" . $date . "] - Acción: " . $accion . PHP_EOL;

        //escribir en el archivo
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
        echo "Se ha registrado la acción de modificación";
    }



    ///*******ERRORES DEL LOG DE ERRORES *************************************/
    public function logError($errorMessage)
    {
        $logFile = __DIR__ . '/../logs/errorLog.txt';

        // Obtener la fecha y hora actual
        $date = date('Y-m-d H:i:s');

        $logMessage = "[" . $date . "] - Error: " . $errorMessage . PHP_EOL;

        //escribir en el archivo
        error_log($logMessage, 3, $logFile);
        echo "Se ha registrado un error en el log de errores";
    }
}
