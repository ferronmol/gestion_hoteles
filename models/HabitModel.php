<?php
//incluyo la conexion a la base de datos
require_once __DIR__ . '/../db/DB.php';
require_once __DIR__ . '/../controllers/LogController.php';

/*********************HABITACION********************************************** */
class Habitacion
{
    private $id;
    private $id_hotel; //FK
    private $num_habitacion;
    private $tipo;
    private $precio;
    private $descripcion;

    // Constructor para crear una instancia de habitacion
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
    // Método para obtener el NUMERO de la habitacion
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

/*****MODELO DE HABITACION ************* */

class habitModel
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

    // Método para obtener el objeto habitacion a partir del id del hotel (id_hotel)
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
    //metodo para crear habitaciones
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
}
