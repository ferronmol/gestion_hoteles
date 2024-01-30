<?php
require_once __DIR__ . '/../db/DB.php';
require_once __DIR__ . '/../controllers/LogController.php';
/*
********************RESERVA**********************************************
*Clase Reserva: Representa una reserva.
*/
class Reserva
{
    private $id;
    private $id_usuario; //FK
    private $id_hotel; //FK
    private $id_habitacion; //FK
    private $fecha_entrada;
    private $fecha_salida;

    /*
    * Constructor de la clase Reserva.
    * @param int $id
    * @param int $id_usuario
    * @param int $id_hotel
    * @param int $id_habitacion
    * @param string $fecha_entrada
    * @param string $fecha_salida
    */
    public function __construct($id, $id_usuario, $id_hotel, $id_habitacion, $fecha_entrada, $fecha_salida)
    {
        $this->id = $id;
        $this->id_usuario = $id_usuario;
        $this->id_hotel = $id_hotel;
        $this->id_habitacion = $id_habitacion;
        $this->fecha_entrada = $fecha_entrada;
        $this->fecha_salida = $fecha_salida;
    }
    // Métodos para obtener INFORMACION de la reserva
    public function getId()
    {
        return $this->id;
    }

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
/*
********************RESERMODEL**********************************************
*Clase ReserModel: Representa el modelo de la reserva.
*/
class reserModel
{

    private $db;
    private $logController;

    /*
    * Constructor de la clase ReserModel.
    * @param DB $db
    * @throws PDOException Si no se puede conectar con la base de datos lanza una excepcion
    */

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
    /*
    * Método para obtener todas las reservas.
    * @return array $reservas Crea un array con todas las reservas
    * @throws PDOException Si no se puede conectar con la base de datos lanza una excepcion
    */
    public function getReserva()
    {
        try {
            $sql = "SELECT * FROM reservas";
            $query = $this->db->getPDO()->prepare($sql);
            $query->execute();
            $reservas = [];
            foreach ($query->fetchAll() as $reserva) {
                $reservas[] = new Reserva($reserva['id'], $reserva['id_usuario'], $reserva['id_hotel'], $reserva['id_habitacion'], $reserva['fecha_entrada'], $reserva['fecha_salida']);
            }
            return $reservas;
        } catch (PDOException $e) {
            exit('Error conectando con la base de datos: ' . $e->getMessage());
        }
    }
    /*
    * Método para obtener todas las reservas de un usuario por su id.
    * @param int $id Id de la reserva
    * @return array $reservas Crea un array con todas las reservas
    * @throws PDOException Si no se puede conectar con la base de datos lanza una excepcion
    */
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
    /*
    * Método para obtener todas las reservas de un usuario por su id.
    * @param int $id_usuario Id del usuario
    * @return array $reservas Crea un array con todas las reservas
    * @throws PDOException Si no se puede conectar con la base de datos lanza una excepcion
    */
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
    /*
    * Método para obtener todas las reservas de un hotel por su id.
    * @param int $id_hotel Id del hotel
    * @return array $reservas Crea un array con todas las reservas
    * @throws PDOException Si no se puede conectar con la base de datos lanza una excepcion
    */
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

    /*
    * Método para obtener todas las reservas de una habitacion por su id.
    * @param int $id_habitacion Id de la habitacion
    * @return array $reservas Crea un array con todas las reservas
    * @throws PDOException Si no se puede conectar con la base de datos lanza una excepcion
    */
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
    /*
    * Método para obtener todas las reservas de una fecha de entrada.
    * @param string $fecha_entrada Fecha de entrada
    * @return array $reservas Crea un array con todas las reservas
    * @throws PDOException Si no se puede conectar con la base de datos lanza una excepcion
    */
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
    /*
    * Método para obtener todas las reservas de una fecha de salida.
    * @param string $fecha_salida Fecha de salida
    * @return array $reservas Crea un array con todas las reservas
    * @throws PDOException Si no se puede conectar con la base de datos lanza una excepcion
    */
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
    /*
    * Método para insertar una reserva.
    * @param int $id_usuario Id del usuario
    * @param int $id_hotel Id del hotel
    * @param int $id_habitacion Id de la habitacion
    * @param string $fecha_entrada Fecha de entrada
    * @param string $fecha_salida Fecha de salida
    * @throws PDOException Si no se puede conectar con la base de datos lanza una excepcion
    */
    public function insertarReserva($id_usuario, $id_hotel, $id_habitacion, $fecha_entrada, $fecha_salida)
    {
        $sql = "INSERT INTO reservas (id_usuario, id_hotel, id_habitacion, fecha_entrada, fecha_salida) VALUES (:id_usuario, :id_hotel, :id_habitacion, :fecha_entrada, :fecha_salida)";
        $query = $this->db->getPDO()->prepare($sql);
        $parameters = array(':id_usuario' => $id_usuario, ':id_hotel' => $id_hotel, ':id_habitacion' => $id_habitacion, ':fecha_entrada' => $fecha_entrada, ':fecha_salida' => $fecha_salida);
        $query->execute($parameters);
    }
}
