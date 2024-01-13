<?php
//incluyo la conexion a la base de datos
require_once '../db/DB.php';

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

    public function __construct(DB $db)
    {
        try {
            if ($this->db->getPDO() == null) {
                echo "No estas conectado con la base de datos";
            }
        } catch (PDOException $ex) {
            echo "Error de conexion con la base de datos";
        }
    }

    // Método para obtener todas las habitaciones
    public function getHabitaciones()
    {
        $habitaciones = [];
        $sql = 'SELECT * FROM habitaciones';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $habitaciones = $stmt->fetchAll(PDO::FETCH_FUNC, 'habitacionFactory');
        return $habitaciones;
    }
    // Método para obtener una habitacion por su id
    public function getHabitacionById($id)
    {
        $sql = 'SELECT * FROM habitaciones WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $habitacion = $stmt->fetchObject('Habitacion');
        return $habitacion;
    }
    // Método para obtener una habitacion por su numero
    public function getHabitacionByNum($num_habitacion)
    {
        $sql = 'SELECT * FROM habitaciones WHERE num_habitacion = :num_habitacion';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':num_habitacion' => $num_habitacion]);
        $habitacion = $stmt->fetchObject('Habitacion');
        return $habitacion;
    }
    // Método para obtener una habitacion por su tipo
    public function getHabitacionByTipo($tipo)
    {
        $sql = 'SELECT * FROM habitaciones WHERE tipo = :tipo';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':tipo' => $tipo]);
        $habitacion = $stmt->fetchObject('Habitacion');
        return $habitacion;
    }
    // Método para obtener una habitacion por su precio
    public function getHabitacionByPrecio($precio)
    {
        $sql = 'SELECT * FROM habitaciones WHERE precio = :precio';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':precio' => $precio]);
        $habitacion = $stmt->fetchObject('Habitacion');
        return $habitacion;
    }
}
