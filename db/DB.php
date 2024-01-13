<?php
require_once '../config/Config.php';

class DB
{
    private $pdo;

    public function __construct()
    {
        $host = DB_HOST;
        $databaseName = DB_NAME;
        $user = DB_USER;
        $password = DB_PASSWORD;
        $pdo = new PDO($host, $user, $password);

        try {
            // Crea una instancia de PDO para conectarse a la base de datos
            $dsn = "mysql:host=$host;dbname=$databaseName;charset=utf8";
            $this->pdo = new PDO($dsn, $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo 'La conexion se ha establecido correctamente';
        } catch (PDOException $ex) {
            echo 'La conexion aun no esta establecida';
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
