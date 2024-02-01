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
            // le mando al controlador el error
            throw new Exception("Error al insertar el usuario en la base de datos: " . $e->getMessage());
            $this->logController->logError("Error insertar usuario" . $e->getMessage());
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
    * Método para modificar una reserva.
    * @param Reserva $reserva Objeto Reserva
    * @return boolean Devuelve true si se ha modificado correctamente
    * @throws PDOException Si no se puede conectar con la base de datos lanza una excepcion
    */
    public function modificarReserva($reserva)
    {
        // var_dump($reserva);
        try {
            $sql = 'UPDATE reservas SET fecha_entrada = :fecha_entrada, fecha_salida = :fecha_salida, id_usuario = :id_usuario, id_hotel = :id_hotel, id_habitacion = :id_habitacion WHERE id = :id';
            $query = $this->db->getPDO()->prepare($sql);
            $parameters = array(
                ':id' => $reserva->getId(),
                ':fecha_entrada' => $reserva->getFecha_entrada(),
                ':fecha_salida' => $reserva->getFecha_salida(),
                ':id_usuario' => $reserva->getId_usuario(),
                ':id_hotel' => $reserva->getId_hotel(),
                ':id_habitacion' => $reserva->getId_habitacion()
            );
            $query->execute($parameters);
            return true;
        } catch (PDOException $e) {
            // le mando al controlador el error         
            $this->logController->logError("Error insertar usuario" . $e->getMessage());
            throw new Exception("Error al insertar el usuario en la base de datos: " . $e->getMessage());
        }
    }

    /*
    * Metodo para borrar una reserva con  su id
    * @param int $id
    */
    public function eliminarReserva($id)
    {
        try {
            $query = "DELETE FROM reservas  WHERE id = :id";
            $stmt = $this->db->getPDO()->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $this->logController->logError('' . $e->getMessage());
        }
    }
    /*
    * Metodo para saber si esta disponible una habitacion en un rango de fechas
    * @param int $id   id d ela habitacion
    * @param date $fecha_entrada
    * @param date $fecha_salida
    * @param bool true si esta disponible
    */
    public function habitacionDisponible($id_habitacion, $fecha_entrada, $fecha_salida)
    {
        try {
            // Consulta para verificar la disponibilidad de la habitación
            $sql = "SELECT COUNT(*) as total
                FROM reservas
                WHERE id_habitacion = :id_habitacion
                AND ((fecha_entrada >= :fecha_entrada AND fecha_entrada < :fecha_salida)
                OR (fecha_salida > :fecha_entrada AND fecha_salida <= :fecha_salida)
                OR (fecha_entrada <= :fecha_entrada AND fecha_salida >= :fecha_salida))";

            // Preparar la consulta
            $stmt = $this->db->getPDO()->prepare($sql);

            // Bind de parámetros
            $stmt->bindParam(':id_habitacion', $id_habitacion, PDO::PARAM_INT);
            $stmt->bindParam(':fecha_entrada', $fecha_entrada, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_salida', $fecha_salida, PDO::PARAM_STR);

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener el resultado
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar si hay reservas en esas fechas
            $totalReservas = isset($row['total']) ? (int)$row['total'] : 0;

            // La habitación está disponible si no hay reservas en esas fechas
            return $totalReservas === 0;
        } catch (PDOException $e) {
            // le mando al controlador el error
            $this->logController->logError("Error insertar usuario" . $e->getMessage());
            throw new Exception("Error al insertar el usuario en la base de datos: " . $e->getMessage());
        }
    }

    /*
    * Crea una nueva reserva y la inserta en la base de datos.
    *
    * @param Reserva $reserva El objeto Reserva que se va a insertar.
    *
    * @return bool True si la inserción fue exitosa, false en caso contrario.
    * @throws Exception Si ocurre un error durante la inserción.
   */
    public function crearReserva($reserva)
    {
        try {
            // Preparar la consulta de inserción
            $query = "INSERT INTO reservas (id_usuario, id_hotel, id_habitacion, fecha_entrada, fecha_salida)
                VALUES (:id_usuario, :id_hotel, :id_habitacion, :fecha_entrada, :fecha_salida)";

            // Preparar la sentencia
            $stmt = $this->db->getPDO()->prepare($query);

            // Obtener los valores de las propiedades de la reserva
            $idUsuario = $reserva->getId_usuario();
            $idHotel = $reserva->getId_hotel();
            $idHabitacion = $reserva->getId_habitacion();
            $fechaEntrada = $reserva->getFecha_Entrada();
            $fechaSalida = $reserva->getFecha_Salida();

            // Vincular parámetros
            $stmt->bindParam(":id_usuario", $idUsuario, PDO::PARAM_INT);
            $stmt->bindParam(":id_hotel", $idHotel, PDO::PARAM_INT);
            $stmt->bindParam(":id_habitacion", $idHabitacion, PDO::PARAM_INT);
            $stmt->bindParam(":fecha_entrada", $fechaEntrada, PDO::PARAM_STR);
            $stmt->bindParam(":fecha_salida", $fechaSalida, PDO::PARAM_STR);

            // Ejecutar la consulta
            $stmt->execute();

            // Asignar el nuevo ID generado automáticamente a la reserva
            $reserva->setId($this->db->getPDO()->lastInsertId());

            // Retornar la reserva con el nuevo ID
            return $reserva;
        } catch (PDOException $ex) {
            throw new RuntimeException('Error al insertar la reserva en la base de datos');
            $this->logController->logError('Error al insertar la reserva en la base de datos: ' . $ex->getMessage());
        }
    }
}
