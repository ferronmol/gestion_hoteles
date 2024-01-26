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
        try {
            if ($this->db->getPDO() == null) {
                $this->logController->errorLog('Error PDO nulo');
            }
        } catch (PDOException $ex) {
            $this->logController->errorLog($ex->getMessage());
        }
    }

    // Método para obtener el objeto habitacion a partir del id del hotel (id_hotel)
    public function getHabitaciones($id_hotel)
    {
        $habitacion = null;
        try {
            $pdoInstance = $this->db->getPDO();
            $sql = "SELECT * FROM habitacion WHERE id_hotel = :id_hotel";
            $stmt = $pdoInstance->prepare($sql);
            $stmt->bindParam(':id_hotel', $id_hotel);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result != null) {
                $habitaciones = new Habitacion($result['id'], $result['id_hotel'], $result['num_habitacion'], $result['tipo'], $result['precio'], $result['descripcion']);
            }
        } catch (PDOException $ex) {
            $this->logController->errorLog($ex->getMessage());
        }
        return $habitaciones;
    }
}
