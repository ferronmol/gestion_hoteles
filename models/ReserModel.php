<?php
require_once __DIR__ . '/../db/DB.php';
require_once __DIR__ . '/../controllers/LogController.php';

class Reserva
{
    private $id;
    private $id_usuario; //FK
    private $id_hotel; //FK
    private $id_habitacion; //FK
    private $fecha_entrada;
    private $fecha_salida;

    // Constructor para crear una instancia de reserva
    public function __construct($id, $id_usuario, $id_hotel, $id_habitacion, $fecha_entrada, $fecha_salida)
    {
        $this->id = $id;
        $this->id_usuario = $id_usuario;
        $this->id_hotel = $id_hotel;
        $this->id_habitacion = $id_habitacion;
        $this->fecha_entrada = $fecha_entrada;
        $this->fecha_salida = $fecha_salida;
    }
    public function getId()
    {
        return $this->id;
    }
    // Método para obtener la fecha de entrada
    public function getFecha_entrada()
    {
        return $this->fecha_entrada;
    }
    public function getFecha_salida()
    {
        return $this->fecha_salida;
    }
    public function getId_usuario()
    {
        return $this->id_usuario;
    }
    public function getId_hotel()
    {
        return $this->id_hotel;
    }
    public function getId_habitacion()
    {
        return $this->id_habitacion;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setFecha_entrada($fecha_entrada)
    {
        $this->fecha_entrada = $fecha_entrada;
    }
    public function setFecha_salida($fecha_salida)
    {
        $this->fecha_salida = $fecha_salida;
    }
    public function setId_usuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }
    public function setId_hotel($id_hotel)
    {
        $this->id_hotel = $id_hotel;
    }
}

class reserModel
{

    private $db;
    private $logController;

    public function __construct(DB $db)
    {
        try {
            $this->db = $db;
            if ($this->db->getPDO() == null) {
                echo "No estas conectado con la base de datos";
            }
        } catch (PDOException $e) {
            exit('Error conectando con la base de datos: ' . $e->getMessage());
        }
    }
    // Método para obtener todas las reservas
    public function getReserva()
    {
        $sql = "SELECT * FROM reservas";
        $query = $this->db->getPDO()->prepare($sql);
        $query->execute();
        $reservas = [];
        foreach ($query->fetchAll() as $reserva) {
            $reservas[] = new Reserva($reserva['id'], $reserva['id_usuario'], $reserva['id_hotel'], $reserva['id_habitacion'], $reserva['fecha_entrada'], $reserva['fecha_salida']);
        }
        return $reservas;
    }
    // Método para obtener una reserva por su id (LO USO EN EDITAR RESERVA)
    public function getById($id)
    {
        $sql = "SELECT * FROM reservas WHERE id = :id";
        $query = $this->db->getPDO()->prepare($sql);
        $parameters = array(':id' => $id);
        $query->execute($parameters);
        // fetch() es el método que obtiene el resultado de la consulta
        // en forma de un array asociativo
        $reserva = $query->fetch();
        return new Reserva($reserva['id'], $reserva['id_usuario'], $reserva['id_hotel'], $reserva['id_habitacion'], $reserva['fecha_entrada'], $reserva['fecha_salida']);
    }
    // Método para obtener una reserva por su id de usuario
    public function getByIdUsuario($id_usuario)
    {
        $sql = "SELECT * FROM reservas WHERE id_usuario = :id_usuario";
        $query = $this->db->getPDO()->prepare($sql);
        $parameters = array(':id_usuario' => $id_usuario);
        $query->execute($parameters);
        // fetch() es el método que obtiene el resultado de la consulta
        // en forma de un array asociativo
        $reserva = $query->fetch();
        return new Reserva($reserva['id'], $reserva['id_usuario'], $reserva['id_hotel'], $reserva['id_habitacion'], $reserva['fecha_entrada'], $reserva['fecha_salida']);
    }
    // Método para obtener una reserva por su id de hotel
    public function getByIdHotel($id_hotel)
    {
        $sql = "SELECT * FROM reserva WHERE id_hotel = :id_hotel";
        $query = $this->db->getPDO()->prepare($sql);
        $parameters = array(':id_hotel' => $id_hotel);
        $query->execute($parameters);
        // fetch() es el método que obtiene el resultado de la consulta
        // en forma de un array asociativo
        $reserva = $query->fetch();
        return new Reserva($reserva['id'], $reserva['id_usuario'], $reserva['id_hotel'], $reserva['id_habitacion'], $reserva['fecha_entrada'], $reserva['fecha_salida']);
    }
    // Método para obtener una reserva por su id de habitación
    public function getByIdHabitacion($id_habitacion)
    {
        $sql = "SELECT * FROM reserva WHERE id_habitacion = :id_habitacion";
        $query = $this->db->getPDO()->prepare($sql);
        $parameters = array(':id_habitacion' => $id_habitacion);
        $query->execute($parameters);
        // fetch() es el método que obtiene el resultado de la consulta
        // en forma de un array asociativo
        $reserva = $query->fetch();
        return new Reserva($reserva['id'], $reserva['id_usuario'], $reserva['id_hotel'], $reserva['id_habitacion'], $reserva['fecha_entrada'], $reserva['fecha_salida']);
    }
    // Método para obtener una reserva por su fecha de entrada
    public function getByFechaEntrada($fecha_entrada)
    {
        $sql = "SELECT * FROM reserva WHERE fecha_entrada = :fecha_entrada";
        $query = $this->db->getPDO()->prepare($sql);
        $parameters = array(':fecha_entrada' => $fecha_entrada);
        $query->execute($parameters);
        // fetch() es el método que obtiene el resultado de la consulta
        // en forma de un array asociativo
        $reserva = $query->fetch();
        return new Reserva($reserva['id'], $reserva['id_usuario'], $reserva['id_hotel'], $reserva['id_habitacion'], $reserva['fecha_entrada'], $reserva['fecha_salida']);
    }
    // Método para obtener una reserva por su fecha de salida
    public function getByFechaSalida($fecha_salida)
    {
        $sql = "SELECT * FROM reservas WHERE fecha_salida = :fecha_salida";
        $query = $this->db->getPDO()->prepare($sql);
        $parameters = array(':fecha_salida' => $fecha_salida);
        $query->execute($parameters);
        // fetch() es el método que obtiene el resultado de la consulta
        // en forma de un array asociativo
        $reserva = $query->fetch();
        return new Reserva($reserva['id'], $reserva['id_usuario'], $reserva['id_hotel'], $reserva['id_habitacion'], $reserva['fecha_entrada'], $reserva['fecha_salida']);
    }
    // Método para insertar una reserva
    public function insertarReserva($id_usuario, $id_hotel, $id_habitacion, $fecha_entrada, $fecha_salida)
    {
        $sql = "INSERT INTO reservas (id_usuario, id_hotel, id_habitacion, fecha_entrada, fecha_salida) VALUES (:id_usuario, :id_hotel, :id_habitacion, :fecha_entrada, :fecha_salida)";
        $query = $this->db->getPDO()->prepare($sql);
        $parameters = array(':id_usuario' => $id_usuario, ':id_hotel' => $id_hotel, ':id_habitacion' => $id_habitacion, ':fecha_entrada' => $fecha_entrada, ':fecha_salida' => $fecha_salida);
        $query->execute($parameters);
    }
}
