<?php
//tendre que usar getPDO() para obtener la conexion a la base de datos
require_once __DIR__ . '/../db/DB.php';
require_once __DIR__ . '/../controllers/LogController.php';

/**
 * *********************USUARIOS**********************************************
 * Clase Usuario: Representa un usuario de la aplicación.
 * Esta clase se encarga de gestionar la información de los usuarios.
 * @param int $id Identificador del usuario
 * @param string $nombre Nombre del usuario
 * @param string $contraseña Contraseña del usuario
 * @param string $fregistro Fecha de registro del usuario
 * @param string $rol Rol del usuario
 * 
 */
class Usuario
{
    private $id;
    private $nombre;
    private $contraseña;
    private $fregistro;
    private $rol;

    /**
     * Constructor de la clase Usuario.
     * @param int $id
     * @param string $nombre
     * @param string $contraseña
     * @param string $fregistro
     * @param string $rol
     */
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
    // MétodoS para obtener INFORMACIÓN del usuario
    public function getNombre()
    {
        return $this->nombre;
    }
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


/*
**************************MODELO DE USUARIOS**************************************
* Clase UserModel: Representa el modelo (Lógica de negocio) de usuarios de la aplicación.
* Esta clase se encarga de realizar las operaciones con la base de datos.
* @param DB $db Instancia de la clase DB
* @param LogController $logController Instancia de la clase LogController
*/

class UserModel
{
    private $db;
    private $logController;

    /**
     * Constructor de la clase UserModel.
     * @param DB $db Instancia de la clase DB
     * @param LogController $logController Instancia de la clase LogController
     * @throws Exception Si no se puede conectar con la base de datos salta una excepción
     */

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

    /*
    **************************METODOS PARA LA BASE DE DATOS**************************************
    /*
    /*
    * Método para abrir la base de datos
    */
    public function cierroBD()
    {
        $this->db->cierroBD();
    }

    /*
    **************************METODOS PARA USUARIOS**************************************
    */

    /**
     * Método para obtener todos los usuarios
     * @param string $nombre Nombre del usuario
     * @param string $contraseña Contraseña del usuario
     * @param string $fregistro Fecha de registro del usuario
     * @param string $rol Rol del usuario
     * @throws Exception Si no se puede conectar con la base de datos salta una excepción
     */
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



    /**
     * Método para obtener todos los usuarios
     * @param string $nombre Nombre del usuario
     * @return array $usuarios Array de objetos Usuario
     * @throws Exception Si no se puede conectar con la base de datos salta una excepción
     */

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

    /**
     * Método para obtener todos los usuarios
     * @param string $nombre Nombre del usuario
     * @param string $contraseña Contraseña del usuario
     * @return boolean $result Resultado de la verificación de las credenciales
     * @throws Exception Si no se puede conectar con la base de datos salta una excepción
     */
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
    /**
     *metodo para saber si existe un usuario
     * @param $id Id del usuario
     * @return bool
     */
    public function usuarioExiste($id)
    {
        try {
            // Preparar la consulta
            $query = "SELECT COUNT(*) as count FROM usuarios WHERE id = :id";

            // Preparar la sentencia
            $stmt = $this->db->getPDO()->prepare($query);

            // Vincular parámetros
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener el resultado
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Comprobar si el usuario existe (count > 0)
            return ($result['count'] > 0);
        } catch (PDOException $ex) {
            throw new RuntimeException('Error al verificar las credenciales del usuario');
            $this->logController->logError('Error al verificar las credenciales del usuario');
            return false;
        }
    }
    /**
     * Metodo apara obtener el nombre de un usuario por su id
     * @param int $id Id del usuario
     * @return string Nombre del usuario
     */
    public function cogerNombreUsuario($id)
    {
        try {
            $sql = "SELECT nombre FROM usuarios WHERE id = :id";
            $stmt = $this->db->getPDO()->prepare($sql);
            $stmt->execute(['id' => $id]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            return $usuario;
        } catch (PDOException $ex) {
            throw new RuntimeException('Error al obtener el nombre del usuario');
            $this->logController->logError('Error al obtener el nombre del usuario');
            return false;
        }
    }
}
