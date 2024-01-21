<?php
$configFilePath = __DIR__ . '/../config/Config.php';

if (file_exists($configFilePath)) {
    require_once($configFilePath);
} else {
    throw new Exception('Config file not found');
    exit;
}
class DB
{
    private $pdo;

    public function __construct()
    {
        $host = DB_HOST;
        $databaseName = DB_NAME;
        $user = DB_USER;
        $password = DB_PASSWORD;

        try {
            // Crea una instancia de PDO para conectarse a la base de datos
            $dsn = "mysql:host=$host;dbname=$databaseName;charset=utf8";
            $this->pdo = new PDO($dsn, $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) {
            header("Location: ../userController.php?accion=mostratError&mensaje=Error al conectar con la base de datos");
        }
    }


    // Obtén la instancia de PDO para interactuar con la base de datos
    public function getPDO()
    {
        return $this->pdo;
    }


    //cerra conexion con la base de datos
    public function cierroBD()
    {
        $this->pdo = null;
    }
}
