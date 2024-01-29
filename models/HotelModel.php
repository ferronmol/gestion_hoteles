<?php
//incluyo la conexion a la base de datos
require_once __DIR__ . '/../db/DB.php';
require_once __DIR__ . '/../controllers/LogController.php';


/*********************HOTEL********************************************** */
class Hotel
{
    private $id;
    private $nombre;
    private $direccion;
    private $ciudad;
    private $pais;
    private $num_habitaciones;
    private $descripcion;
    private $foto;  //MEDIUMBLOB

    // Constructor para crear una instancia de Hotel
    public function __construct($id, $nombre, $direccion, $ciudad, $pais, $num_habitaciones, $descripcion, $foto)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->direccion = $direccion;
        $this->ciudad = $ciudad;
        $this->pais = $pais;
        $this->num_habitaciones = $num_habitaciones;
        $this->descripcion = $descripcion;
        $this->foto = $foto;
    }
    public function getId()
    {
        return $this->id;
    }
    // Método para obtener el nombre del hotel
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getDireccion()
    {
        return $this->direccion;
    }
    public function getCiudad()
    {
        return $this->ciudad;
    }
    public function getPais()
    {
        return $this->pais;
    }
    public function getNum_habitaciones()
    {
        return $this->num_habitaciones;
    }
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    public function getFoto()
    {
        return $this->foto;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;
    }
    public function setPais($pais)
    {
        $this->pais = $pais;
    }
    public function setNum_habitaciones($num_habitaciones)
    {
        $this->num_habitaciones = $num_habitaciones;
    }
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }
    public function setFoto($foto)
    {
        $this->foto = $foto;
    }
}
/*************************modelo de HOTEL   ******************************/

class hotelModel
{
    //abro conexion con la base de datos
    private $db;
    private $logController;

    public function __construct(DB $db)
    {
        $this->db = $db;
        $this->logController = new LogController();
        //verificar la conexion y manejar errores
        try {
            if ($this->db->getPDO() == null) {

                $this->logController->logError("No estas conectado con la base de datos");
            }
        } catch (PDOException $ex) {
            $this->logController->logError("Error de conexion con la base de datos");
        }
    }

    /*
    **************************METODOS PARA HOTELES**************************************
     */
    //metodo para obtener todos los objetos hotel en un array
    public function getHotel($nombre)
    {
        try {
            $sql = "SELECT * FROM hoteles WHERE nombre LIKE '%$nombre%'";
            $stmt = $this->db->getPDO()->prepare($sql);
            $stmt->execute();

            $hotel = $stmt->fetchObject('Hotel');
            return $hotel;  //devuelvo el objeto hotel

        } catch (Exception $ex) {
            // le mando al controlador el error
            $this->logController->logError("Detalles: " . $ex->getMessage());
            return null;
        }
    }
    //metodo para listar los hoteles
    public function cogerHoteles()
    {
        try {
            $sql = "SELECT * FROM hoteles";
            $stmt = $this->db->getPDO()->prepare($sql);
            $stmt->execute();

            $hoteles = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $hoteles;  //devuelvo el array de hoteles


        } catch (Exception $ex) {
            // le mando al controlador el error
            $this->logController->logError("Detalles: " . $ex->getMessage());
            return null;
        }
    }
    //metodo para obtener un hotel por su id
    public function getHotelById($id)
    {
        try {
            $sql = "SELECT * FROM hoteles WHERE id = $id";
            $stmt = $this->db->getPDO()->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            // hago un fetch_assoc para obtener un array asociativo
            if ($result) {
                // Crear una instancia de Hotel y pasar los valores al constructor
                $hotel = new Hotel(
                    $result['id'],
                    $result['nombre'],
                    $result['direccion'],
                    $result['ciudad'],
                    $result['pais'],
                    $result['num_habitaciones'],
                    $result['descripcion'],
                    $result['foto']
                );

                return $hotel;  //ya tengo un objeto hotel
            } else {
                return null;
            }
        } catch (Exception $ex) {
            // le mando al controlador el error
            $this->logController->logError("Detalles: " . $ex->getMessage());
            return null;
        }
    }
    public function modificarHotel($id, $nombre, $direccion, $ciudad, $pais, $num_habitaciones, $descripcion, $fotoHotel)
    {
        try {
            // Construir la ruta completa de la foto
            $rutaFotos = __DIR__ . '/../assets/images/fotohoteles/';
            $foto = file_get_contents($rutaFotos . $fotoHotel);

            // Consulta SQL con parámetros con nombres
            $sql = "UPDATE hoteles SET nombre = :nombre, direccion = :direccion, ciudad = :ciudad, pais = :pais, num_habitaciones = :num_habitaciones, descripcion = :descripcion, foto = :foto WHERE id = :id";

            // Preparar la consulta
            $stmt = $this->db->getPDO()->prepare($sql);

            // Vincular los parámetros
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->bindParam(':ciudad', $ciudad);
            $stmt->bindParam(':pais', $pais);
            $stmt->bindParam(':num_habitaciones', $num_habitaciones);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':foto', $foto);
            $stmt->bindParam(':id', $id);

            // Ejecutar la consulta
            $stmt->execute();

            return true;  // Devuelve true si se ha modificado correctamente

        } catch (Exception $ex) {
            // Manejar el error y devolver false
            $this->logController->logError("Detalles: " . $ex->getMessage());
            return false;
        }
    }
}
