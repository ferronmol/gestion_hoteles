<?php
//incluyo la conexion a la base de datos
require_once __DIR__ . '/../db/DB.php';
require_once __DIR__ . '/../controllers/LogController.php';


/*
********************HOTEL********************************************** 
*Clase Hotel: Representa un hotel.
*/
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

    /*
    * Constructor de la clase Hotel.
    * @param int $id
    * @param string $nombre
    * @param string $direccion
    * @param string $ciudad
    * @param string $pais
    * @param int $num_habitaciones
    * @param string $descripcion
    * @param string $foto
    */
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
    // Métodos para obtener INFORMACION del hotel
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
/*
************************modelo de HOTEL   *****************************
* Clase hotelModel: Contiene los metodos para trabajar con la base de datos.
*/

class hotelModel
{
    //abro conexion con la base de datos
    private $db;
    private $logController;

    /*
    * Constructor de la clase hotelModel.
    * @param DB $db Instancia de la clase DB
    * @param LogController $logController Instancia de la clase LogController
    * @thows Exception Si no se puede conectar con la base de datos salta una excepcion
    */

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
    /*
    * Metodo para coger un hotel en la base de datos por su nombre
    * @param string $nombre
    * @return Hotel $hotel Devuelve un objeto hotel
    * @return null Si no se encuentra el hotel devuelve null
    * @throws Exception Si hay un error al ejecutar la consulta salta una excepcion
    */

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

    /*
    * Metodo para coger todos los hoteles de la base de datos
    * @return array $hoteles Devuelve un array con todos los hoteles
    * @return null Si no se encuentra ningun hotel devuelve null
    * @throws Exception Si hay un error al ejecutar la consulta salta una excepcion
    */

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

    /*
    * Metodo para coger un hotel de la base de datos por su id
    * @param int $id
    * @return Hotel $hotel Devuelve un objeto hotel
    * @return null Si no se encuentra el hotel devuelve null
    * @throws Exception Si hay un error al ejecutar la consulta salta una excepcion
    */
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

    /*
    * Método para modificar un hotel en la base de datos
    * @param int $id
    * @param string $nombre
    * @param string $direccion
    * @param string $ciudad
    * @param string $pais
    * @param int $num_habitaciones
    * @param string $descripcion
    * @param string $fotoHotel Nombre de la foto del hotel sin la ruta
    * @return boolean Devuelve true si se ha modificado correctamente
    * @throws Exception Si hay un error al ejecutar la consulta salta una excepcion
    */
    public function modificarHotel($id, $nombre, $direccion, $ciudad, $pais, $num_habitaciones, $descripcion, $fotoHotel)
    {
        try {
            $foto = null;
            //verificar si se proporciona una foto
            if ($fotoHotel != null) {
                // Construir la ruta completa de la foto
                $rutaFotos = __DIR__ . '/../assets/images/fotohoteles/';
                $foto = file_get_contents($rutaFotos . $fotoHotel);
            }
            if ($foto === false) {
                $this->logController->logError("No se ha podido cargar la foto");
                return false;
            }
            // Consulta SQL con parámetros con nombres
            $sql = "UPDATE hoteles SET nombre = :nombre, direccion = :direccion, ciudad = :ciudad, pais = :pais, num_habitaciones = :num_habitaciones, descripcion = :descripcion";
            // SOLO SI SE PROPORCIONA UNA FOTO AÑADIR EL CAMPO FOTO A LA CONSULTA
            if ($foto !== null) {
                $sql .= ", foto = :foto";
            }
            $sql .= " WHERE id = :id";
            // Preparar la consulta
            $stmt = $this->db->getPDO()->prepare($sql);

            // Vincular los parámetros
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->bindParam(':ciudad', $ciudad);
            $stmt->bindParam(':pais', $pais);
            $stmt->bindParam(':num_habitaciones', $num_habitaciones);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':id', $id);

            if ($foto !== null) {
                $stmt->bindParam(':foto', $foto, PDO::PARAM_LOB);
            }
            // Ejecutar la consulta
            $stmt->execute();

            return true;  // Devuelve true si se ha modificado correctamente

        } catch (Exception $ex) {
            // Manejar el error y devolver false
            $this->logController->logError("Detalles: " . $ex->getMessage());
            return false;
        }
    }
    /*
    *Metodo boolenano para saber si existe un Hotel
    * @param int $id
    * @return bool
    */
    public function hotelExiste($id)
    {
        try {
            // Preparar la consulta
            $query = "SELECT COUNT(*) as count FROM hoteles WHERE id = :id";

            // Preparar la sentencia
            $stmt = $this->db->getPDO()->prepare($query);

            // Vincular parámetros
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener el resultado
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Comprobar si el usuario existe (count > 0)
            return ($result['count'] > 0);
        } catch (PDOException $ex) {
            throw new RuntimeException('Error al verificar la existencia del hotel');
            $this->logController->logError('Error al verificar la existencia del hotel');
            return false;
        }
    }
}
