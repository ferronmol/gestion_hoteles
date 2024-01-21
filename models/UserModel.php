
<?php
//tendre que usar getPDO() para obtener la conexion a la base de datos
require_once __DIR__ . '/../db/DB.php';


/*********************USUARIOS********************************************** */
class Usuario
{
    private $id;
    private $nombre;
    private $password;
    private $fregistro;
    private $rol;

    // Constructor para crear una instancia de Usuario
    public function __construct($id, $nombre, $password, $fregistro, $rol)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->password = $password;
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
    public function getPassword()
    {
        return $this->password;
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

    public function __construct(DB $db)
    {
        $this->db = $db;
        //verificar la conexion y manejar errores
        try {
            $this->db->getPDO();
            if ($this->db->getPDO() == null) {
                echo "No estas conectado con la base de datos";
                exit;
            }
        } catch (PDOException $e) {
            echo "<No estas conectado con la base de datos: " . $e->getMessage();
            exit;
        }
        //
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
    public function setUsuario($nombre, $password, $fregistro, $rol)
    {
        try {
            // Encriptación de la contraseña
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO usuarios (nombre, password, fecha_registro, rol) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->getPDO()->prepare($sql); //el getPDO es para obtener la conexion a la base de datos
            $result = $stmt->execute([$nombre, $passwordHash, $fregistro, $rol]);
            //cierro la conexion
            $this->db->cierroBD();
        } catch (Exception $ex) {
            // le mando al controlador el error
            echo '<p class="error">Detalles: ' . $ex->getMessage() . '</p>';
        }
    }


    // Método para verificar si un usuario ya existe, devuelve true si existe y false si no existe
    public function existUsuario($nombre)
    {
        try {
            $sql = "SELECT id FROM usuarios WHERE nombre = ?";
            $stmt = $this->db->getPDO()->prepare($sql);
            $stmt->execute([$nombre]);
            return $stmt->rowCount() > 0;
            //cierro la conexion
            $this->db->cierroBD();
        } catch (Exception $ex) {
            echo '<p class="error">Detalles: ' . $ex->getMessage() . '</p>';
            return false;
        }
    }


    // Método para obtener un objeto usuario por su nombre de usuario

    public function getUsuario($nombre)
    {
        try {
            $sql = "SELECT id, nombre, password, fregistro,rol FROM usuarios WHERE nombre = ?";
            $stmt = $this->db->getPDO()->prepare($sql);
            $stmt->execute([$nombre]);

            $usuario = $stmt->fetchObject('Usuario');
            return $usuario; // Devuelve un objeto Usuario
            //cierro la conexion
            $this->db->cierroBD();
        } catch (Exception $ex) {
            echo '<p class="error">Detalles: ' . $ex->getMessage() . '</p>';
            return null;
        }
    }

    // Método para verificar las credenciales del usuario
    public function verifyCredenciales($nombre, $password)
    {
        try {
            $sql = "SELECT * FROM usuarios WHERE nombre = ?";
            $stmt = $this->db->getPDO()->prepare($sql);
            $stmt->execute([$nombre]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['contraseña'])) {
                // Si el usuario existe y la contraseña coincide, retorna true
                return true;
            } else {
                // Si el usuario no existe o la contraseña no coincide, retorna false
                return false;
            }
        } catch (PDOException $ex) {
            throw new RuntimeException('Error al verificar las credenciales del usuario');
        } finally {
            //cierro la conexion
            $this->db->cierroBD();
        }
    }
}
