<?php
//incluyo la conexion a la base de datos
require_once __DIR__ . '/../db/DB.php';

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
}
/*************************modelo de HOTEL   ******************************/

class hotelModel
{
    //abro conexion con la base de datos
    private $db;

    public function __construct(DB $db)
    {
        $this->db = $db;
        //verificar la conexion y manejar errores
        try {
            if ($this->db->getPDO() == null) {
                echo "No estas conectado con la base de datos";
            }
        } catch (PDOException $ex) {
            echo "Error de conexion con la base de datos";
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
            $this->db->cierroBD();
        } catch (Exception $ex) {
            // le mando al controlador el error
            echo '<p class="error">Detalles: ' . $ex->getMessage() . '</p>';
            return null;
        }
    }
    //metodo para listar los hoteles
    public function mostrarHoteles()
    {
        try {
            $sql = "SELECT * FROM hoteles";
            $stmt = $this->db->getPDO()->prepare($sql);
            $stmt->execute();

            $hoteles = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $hoteles;  //devuelvo el array de hoteles
            $this->db->cierroBD();
        } catch (Exception $ex) {
            // le mando al controlador el error
            echo '<p class="error">Detalles: ' . $ex->getMessage() . '</p>';
            return null;
        }
    }
}
