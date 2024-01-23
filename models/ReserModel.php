<?php
require_once __DIR__ . '/../db/DB.php';

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
}

class reserModel
{

    private $db;

    public function __construct(DB $db)
    {
        try {
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
        $reservas = [];
        $sql = "SELECT * FROM reserva";
        $query = $this->db->prepare($sql);
        $query->execute();
        // fetch() es el método que obtiene el resultado de la consulta
        // en forma de un array asociativo
        foreach ($query->fetchAll() as $reserva) {
            $reservas[] = new Reserva($reserva['id'], $reserva['id_usuario'], $reserva['id_hotel'], $reserva['id_habitacion'], $reserva['fecha_entrada'], $reserva['fecha_salida']);
        }
        return $reservas;
    }
    // Método para obtener una reserva por su id
    public function getById($id)
    {
        $sql = "SELECT * FROM reserva WHERE id = :id";
        $query = $this->db->prepare($sql);
        $parameters = array(':id' => $id);
        $query->execute($parameters);
        // fetch() es el método que obtiene el resultado de la consulta
        // en forma de un array asociativo
        $reserva = $query->fetch();
        return new Reserva($reserva['id'], $reserva['id_usuario'], $reserva['id_hotel'], $reserva['id_habitacion'], $reserva['fecha_entrada'], $reserva['fecha_salida']);
    }
}
