<?php
include_once("./controllers/LogController.php");
include_once("./controllers/HotelController.php");
include_once("./views/habitView.php");
include_once("./views/hotelView.php");
include_once("./views/modView.php");
include_once("./models/HabitModel.php");
include_once("./models/hotelModel.php");
include_once("./config/Config.php");

if (!isset($_SESSION)) {
    header('Location: index.php?controller=User&action=mostrarInicio');
    exit();
}
class GestController
{
    private $logController;
    private $hotelController;
    private $habitView;
    private $habitModel;
    private $hotelModel;
    private $modView;
    private $hotelView;

    public function __construct()
    {
        $this->logController = new LogController();
        $this->hotelController = new HotelController();
        $this->habitView = new HabitView();
        $this->habitModel = new HabitModel(new DB());
        $this->hotelModel = new HotelModel(new DB());
        $this->modView = new ModView();
        $this->hotelView = new HotelView();
    }
    //esat funcion es inicio de la visualizacion de habitaciones
    public function mostrarHabitaciones()
    {

        //recibo el id del hotel y la ciudad
        if (isset($_POST['id_hotel']) && isset($_POST['ciudad'])) {
            $id_hotel = $_POST['id_hotel'];
            $ciudad = $_POST['ciudad'];
        } else {
            $this->logController->logError('error al recuperar el id del hotel y la ciudad');
            return;
        }
        //tengo que obtener las habitaciones
        $habitaciones = $this->habitModel->getHabitaciones($id_hotel);
        //llamo al metodo que muestra las habitaciones
        $this->listarHabitaciones($habitaciones, $ciudad);
        $this->habitView->mostrarInicio(); //ok
    }
    //funcion para listar las habitaciones
    public function listarHabitaciones($habitaciones, $ciudad)
    {
        //var_dump($habitaciones);
        $this->habitView->listarHabitaciones($habitaciones, $ciudad); //ok
    }
    //funcion para mostrar el formulario de modificacion
    public function mostrarFormularioCrearHabitaciones()
    {
        //recibo el id del hotel
        if (isset($_POST['id_hotel'])) {
            $id_hotel = $_POST['id_hotel'];
        } else {
            $this->logController->logError('error al recuperar el id del hotel');
        }
        $this->modView->CrearHabitaciones($id_hotel);
    }
    /************************OBTENER DATOS POR ID PARA RELLENAR FORM ***********************/
    public function obtenerHotelesPorId()
    {
        if (isset($_SESSION['usuario']) && $_SESSION['usuario'] !== null) {
            //recibo el id del hotel
            if (isset($_POST['id'])) {
                $id = $_POST['id'];
            } else {
                $this->logController->logError('error al recuperar el id del hotel');
            }
            $hotel = $this->hotelModel->getHotelById($id);
            //var_dump($hotel);
            if ($hotel) {
                $this->mostrarFormularioMod($hotel);
            } else {
                $this->logController->logError('error al obtener el hotel por id');
            }
        }
    }

    public function obtenerHabitacionPorId()
    {
        if (isset($_SESSION['usuario']) && $_SESSION['usuario'] !== null) {
            //recibo el id de la habitación qu eme pasa el boton de modificar
            if (isset($_POST['id'])) {
                $id = $_POST['id'];
            } else {
                $this->logController->logError('error al recuperar el id de la habitación');
            }
            $habitacion = $this->habitModel->getHabitacionById($id);
            //var_dump($habitacion);
            if ($habitacion) {
                $this->mostrarFormularioModHabitacion($habitacion);
            } else {
                $this->logController->logError('error al obtener el hotel por id');
            }
        }
    }
    /************************ FIN OBTENER DATOS POR ID PARA RELLENAR FORM ***********************/
    public function mostrarFormularioMod($hotel)
    {
        $this->modView->mostrarFormularioMod($hotel);
    }

    public function mostrarFormularioModHabitacion($habitacion)
    {
        $this->modView->mostrarFormularioModHabitaciones($habitacion);
    }
    /**********************************PROCESAMIENTO DE FORMULARIOS******************************* */
    //funcion para procesar el formulario de modificacion DE HOTELES
    public function recibirFormularioMod()
    {
        //     TEMA DE LA FOTO
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $fotoHotel = $_FILES['foto']['name'];
            $rutaTempHotel = $_FILES['foto']['tmp_name'];
            $rutaFotos = __DIR__ . '/../assets/images/fotohoteles/';
            $rutaDestinoHotel = $rutaFotos . $fotoHotel;

            // Intentar mover el archivo
            if (move_uploaded_file($rutaTempHotel, $rutaDestinoHotel)) {
                $foto = $fotoHotel;
            } else {
                // Manejar error de carga de archivo
                $this->logController->logError('Error al cargar la foto del hotel');
                $foto = null;
            }
        } else {
            $foto = null;
        }
        //recibo los datos del formulario 

        if (isset($_POST['id']) && isset($_POST['nombre']) && isset($_POST['direccion']) && isset($_POST['ciudad']) && isset($_POST['pais']) && isset($_POST['num_habitaciones']) && isset($_POST['descripcion'])) {
            $id = htmlspecialchars($_POST['id']);
            $nombre = htmlspecialchars($_POST['nombre']);
            $direccion = htmlspecialchars($_POST['direccion']);
            $ciudad = htmlspecialchars($_POST['ciudad']);
            $pais = htmlspecialchars($_POST['pais']);
            $num_habitaciones = htmlentities($_POST['num_habitaciones']);
            $descripcion = htmlspecialchars($_POST['descripcion']);

            $this->hotelModel->modificarHotel($id, $nombre, $direccion, $ciudad, $pais, $num_habitaciones, $descripcion);
            //vuelvo a mostrar la lista de hoteles
            $this->hotelController->obtenerHoteles(); //ok
        } else {
            var_dump($_POST);
            echo 'error al recibir los datos del formulario';
        }
    }
    public function recibirFormularioCrearhabitaciones()  // CREAR HABITACIONES
    {
        //recibo los datos del formulario 
        // var_dump($_POST);
        if (isset($_POST['id_hotel']) && isset($_POST['tipo']) && isset($_POST['descripcion']) && isset($_POST['num_habitacion']) && isset($_POST['precio'])) {
            $id_hotel = htmlspecialchars($_POST['id_hotel']);
            $tipo = htmlspecialchars($_POST['tipo']);
            $descripcion = htmlspecialchars($_POST['descripcion']);
            $num_habitacion = htmlentities($_POST['num_habitacion']);
            $precio = htmlspecialchars($_POST['precio']);
            //var_dump($id_hotel, $num_habitacion, $tipo, $precio, $descripcion);
            /******VERIFICACIONES****** */
            // Verifico que la habitación no exista con el mismo número en el mismo hotel
            $habitacionExistente = $this->habitModel->getHabitacionByNumeroYHotel($num_habitacion, $id_hotel);
            if ($habitacionExistente) {
                $this->logController->logError('Ya existe una habitación con el mismo número en el mismo hotel');
                $this->hotelView->inicioHoteles();
            } else {
                //verifio la cantidad actual de habitaciones del hotel
                $habitacionesActuales = $this->habitModel->getHabitaciones($id_hotel);
                $cantidadHabitacionesActuales = count($habitacionesActuales);
                $cantidadMaxima = ($id_hotel == 1) ? 10 : (($id_hotel == 2) ? 20 : 0); //pongo los maximos de habitaciones por hotel

                if ($cantidadHabitacionesActuales >= $cantidadMaxima) {
                    $this->logController->logError('El hotel ya tiene el máximo de habitaciones');
                    $this->hotelView->inicioHoteles();
                } else {
                    $this->habitModel->crearHabitacion($id_hotel, $num_habitacion, $tipo, $precio, $descripcion);
                    $this->habitView->mostrarExito('Habitación creada con éxito');
                    $this->logController->logMod('Se ha creado una habitación');
                }
                //vuelvo a mostrar la lista de hoteles
                $this->hotelView->inicioHoteles();
            }
        } else {
            var_dump($_POST);
            echo 'error al recibir los datos del formulario';
        }
    }
    //funcion para procesar el formulario de modificacion DE HABITACIONES
    public function recibirFormularioModHabitaciones()
    {
        //recibo los datos del formulario 
        if (isset($_POST['id']) && isset($_POST['id_hotel']) && isset($_POST['num_habitacion']) >= 0  && isset($_POST['tipo']) && isset($_POST['precio']) >= 0 && isset($_POST['descripcion'])) {
            $id = htmlspecialchars($_POST['id']);
            $id_hotel = htmlspecialchars($_POST['id_hotel']);
            $num_habitacion = htmlentities($_POST['num_habitacion']);
            $tipo = htmlspecialchars($_POST['tipo']);
            $precio = htmlspecialchars($_POST['precio']);
            $descripcion = htmlspecialchars($_POST['descripcion']);
            //var_dump($id_hotel, $num_habitacion, $tipo, $precio, $descripcion);
            $this->habitModel->modificarHabitacion($id, $id_hotel, $num_habitacion, $tipo, $precio, $descripcion);
            //vuelvo a mostrar la lista de hoteles
            $this->hotelController->inicioHoteles();
        } else {
            var_dump($_POST);
            echo 'error al recibir los datos del formulario';
        }
    }
}
