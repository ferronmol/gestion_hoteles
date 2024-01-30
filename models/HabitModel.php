<?php
//incluyo la conexion a la base de datos
require_once __DIR__ . '/../db/DB.php';
require_once __DIR__ . '/../controllers/LogController.php';

/*
********************HABITACION********************************************** 
*Clase Habitacion: Representa una habitacion de un hotel.
*/
class Habitacion
{
    private $id;
    private $id_hotel; //FK
    private $num_habitacion;
    private $tipo;
    private $precio;
    private $descripcion;

    /*
    * Constructor de la clase Habitacion.
    * @param int $id
    * @param int $id_hotel
    * @param int $num_habitacion
    * @param string $tipo
    * @param float $precio
    * @param string $descripcion
    */
    public function __construct($id, $id_hotel, $num_habitacion, $tipo, $precio, $descripcion)
    {
        $this->id = $id;
        $this->id_hotel = $id_hotel;
        $this->num_habitacion = $num_habitacion;
        $this->tipo = $tipo;
        $this->precio = $precio;
        $this->descripcion = $descripcion;
    }
    public function getId()
    {
        return $this->id;
    }
    // Métodos para obtener INFORMACION de la habitacion
    public function getNum_habitacion()
    {
        return $this->num_habitacion;
    }
    public function getTipo()
    {
        return $this->tipo;
    }
    public function getPrecio()
    {
        return $this->precio;
    }
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    public function getId_hotel()
    {
        return $this->id_hotel;
    }
}

/*
****MODELO DE HABITACION *************
*Clase habitModel Representa una habitacion de un hotel.
*/

class habitModel
{
    private $db;
    private $logController;

    /*
* Constructor de la clase habitModel.
* @param DB $db Instancia de la clase DB
* @param LogController $logController Instancia de la clase LogController
* @thows Exception Si no se puede conectar con la base de datos salta una excepcion
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
    * Método para obtener todas las habitaciones de un hotel
    * @param int $id_hotel
    * @return array $habitaciones Crea un array con todas las habitaciones de un hotel
    * @throws Exception si no se puede conectar con la base de datos salta una excepcion
    */
    public function getHabitaciones($id_hotel)
    {
        $habitaciones = array();
        try {
            $pdoInstance = $this->db->getPDO();
            $sql = "SELECT * FROM habitaciones WHERE id_hotel = :id_hotel";
            $stmt = $pdoInstance->prepare($sql);
            $stmt->bindParam(':id_hotel', $id_hotel);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($results as $result) {
                $habitacion = new Habitacion($result['id'], $result['id_hotel'], $result['num_habitacion'], $result['tipo'], $result['precio'], $result['descripcion']);
                $habitaciones[] = $habitacion;
            }
        } catch (PDOException $ex) {
            $this->logController->logError($ex->getMessage());
        }
        return $habitaciones;
    }

    /*
    * Método para obtener todas las habitaciones de un hotel
    * @param int $id_hotel 
    * @param int $num_habitacion
    * @param string $tipo
    * @param float $precio
    * @param string $descripcion
    * @return array $habitaciones Crea un array con todas las habitaciones de un hotel
    * @throws Exception si no se puede conectar con la base de datos salta una excepcion
    */
    public function crearHabitacion($id_hotel, $num_habitacion, $tipo, $precio, $descripcion)
    {
        try {
            $pdoInstance = $this->db->getPDO();
            $sql = "INSERT INTO habitaciones (id_hotel, num_habitacion, tipo, precio, descripcion) VALUES (:id_hotel, :num_habitacion, :tipo, :precio, :descripcion)";
            $stmt = $pdoInstance->prepare($sql);
            $stmt->bindParam(':id_hotel', $id_hotel);
            $stmt->bindParam(':num_habitacion', $num_habitacion);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':precio', $precio);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->execute();
        } catch (PDOException $ex) {
            $this->logController->logError($ex->getMessage());
        }
    }
    /*
    * Método para obtener una habitacion por su id
    * @param int $id
    * @return Habitacion $habitacion Objeto Habitacion
    * @throws Exception si no se puede conectar con la base de datos salta una excepcion
    */
    public function getHabitacionById($id)
    {
        try {
            $pdoInstance = $this->db->getPDO();
            $sql = "SELECT * FROM habitaciones WHERE id = :id";
            $stmt = $pdoInstance->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $habitacion = new Habitacion($result['id'], $result['id_hotel'], $result['num_habitacion'], $result['tipo'], $result['precio'], $result['descripcion']);
        } catch (PDOException $ex) {
            $this->logController->logError($ex->getMessage());
        }
        return $habitacion;
    }
    /*
    * Método para modificar una habitacion por su id
    * @param int $id
    * @param int $id_hotel
    * @param int $num_habitacion
    * @param string $tipo
    * @param float $precio
    * @param string $descripcion
    * @throws Exception si no se puede conectar con la base de datos salta una excepcion
    */

    public function modificarHabitacion($id, $id_hotel, $num_habitacion, $tipo, $precio, $descripcion)
    {
        try {
            $pdoInstance = $this->db->getPDO();
            $sql = "UPDATE habitaciones SET id_hotel = :id_hotel, num_habitacion = :num_habitacion, tipo = :tipo, precio = :precio, descripcion = :descripcion WHERE id = :id";
            $stmt = $pdoInstance->prepare($sql);
            $stmt->bindParam(':id_hotel', $id_hotel);
            $stmt->bindParam(':num_habitacion', $num_habitacion);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':precio', $precio);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch (PDOException $ex) {
            $this->logController->logError($ex->getMessage());
        }
    }
    /*
    /* Método para verificar si existe una habitacion con el mismo numero en el mismo hotel
    * @param int $num_habitacion
    * @param int $id_hotel
    * @return boolean $rowCount
    * @throws Exception si no se puede conectar con la base de datos salta una excepcion
    */
    public function getHabitacionByNumeroYHotel($num_habitacion, $id_hotel)
    {
        try {
            $pdoInstance = $this->db->getPDO();
            $sql = "SELECT COUNT(*) FROM habitaciones WHERE num_habitacion = :num_habitacion AND id_hotel = :id_hotel";
            $stmt = $pdoInstance->prepare($sql);
            $stmt->bindParam(':num_habitacion', $num_habitacion);
            $stmt->bindParam(':id_hotel', $id_hotel);
            $stmt->execute();
            $rowCount = $stmt->fetchColumn();

            return $rowCount > 0;
        } catch (PDOException $ex) {
            $this->logController->logError($ex->getMessage());
            return false;
        }
    }
}
