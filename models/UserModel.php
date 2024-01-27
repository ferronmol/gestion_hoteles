<?php
//tendre que usar getPDO() para obtener la conexion a la base de datos
require_once __DIR__ . '/../db/DB.php';
require_once __DIR__ . '/../controllers/LogController.php';



/*********************USUARIOS********************************************** */
class Usuario
{
    private $id;
    private $nombre;
    private $contraseña;
    private $fregistro;
    private $rol;

    // Constructor para crear una instancia de Usuario
    public function __construct($id, $nombre, $contraseña, $fregistro, $rol)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->contraseña = $contraseña;
        $this->fregistro = $fregistro;
        $this->rol = $rol;
    }
    public function getId()
    {
        return $this->id;
    }
    // Método para obtener el nombre del usuario
    public function getNombre()
    {
        return $this->nombre;
    }
    // Método para obtener la contraseña del usuario
    public function getContraseña()
    {
        return $this->contraseña;
    }
    public function getFregistro()
    {
        return $this->fregistro;
    }
    public function getRol()
    {
        return $this->rol;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
}


/*************************modelo de usuario******************************/

class UserModel
{
    private $db;
    private $logController;

    public function __construct(DB $db)
    {
        $this->db = $db;
        $this->logController = new LogController();

        // Verificar la conexión y manejar errores
        try {
            $pdoInstance = $this->db->getPDO();

            if ($pdoInstance == null) {
                throw new Exception("No estás conectado con la base de datos ");
                $this->logController->logError("La isntancia de PDO es nula");
            }
        } catch (PDOException $e) {
            // Manejar errores de conexión PDO si es necesario
            throw new Exception("Error de conexión con la base de datos: " . $e->getMessage());
            $this->logController->logError("Error con la base de datos: " . $e->getMessage());
        }
    }


    //metodo para cerrar la conexion
    public function cierroBD()
    {
        $this->db->cierroBD();
    }

    /*
    **************************METODOS PARA USUARIOS**************************************
    */

    // Método para insertar  usuario en la base de datos (despues de validarlo bien)
    public function setUsuario($nombre, $contraseña, $fregistro, $rol)
    {
        try {
            // Encriptación de la contraseña
            $passwordHash = password_hash($contraseña, PASSWORD_DEFAULT);
            $sql = "INSERT INTO usuarios (nombre, contraseña, fecha_registro, rol) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->getPDO()->prepare($sql); //el getPDO es para obtener la conexion a la base de datos
            $result = $stmt->execute([$nombre, $passwordHash, $fregistro, $rol]);
        } catch (Exception $ex) {
            // le mando al controlador el error
            throw new Exception("Error al insertar el usuario en la base de datos: " . $ex->getMessage());
            $this->logController->logError("Error insertar usuario" . $ex->getMessage());
        }
    }



    // Método para obtener un objeto usuario por su nombre de usuario

    public function getUsuario($nombre)
    {
        try {
            $sql = "SELECT * FROM usuarios WHERE nombre = :nombre";
            $stmt = $this->db->getPDO()->prepare($sql);
            $stmt->execute(['nombre' => $nombre]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                $usuario = new Usuario($usuario['id'], $usuario['nombre'], $usuario['contraseña'], $usuario['fecha_registro'], $usuario['rol']);
                return $usuario;
            } else {
                throw new Exception("Error al obtener el usuario de la base de datos: ");
                $this->logController->logError("Error al obtener el usuario de la base de datos: ");
            }
        } catch (Exception $ex) {
            return null;
        }
    }

    // Método para verificar las credenciales del usuario
    public function verifyCredenciales($nombre, $contraseña)
    {
        try {
            $sql = "SELECT * FROM usuarios WHERE nombre = ?";
            $stmt = $this->db->getPDO()->prepare($sql);
            $stmt->execute([$nombre]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC); // Obtengo un array asociativo
            if (!$user) { //añadido tras hacer pruebas
                return false;
            }
            if ($user['nombre'] === $nombre && $user['contraseña'] === $contraseña) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $ex) {
            throw new RuntimeException('Error al verificar las credenciales del usuario');
            $this->logController->logError('Error al verificar las credenciales del usuario');
            return false;
        }
    }
}
