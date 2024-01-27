<?php
include_once("./controllers/LogController.php");
include_once("./controllers/HotelController.php");
include_once("./views/habitView.php");
include_once("./views/hotelView.php");
include_once("./views/modView.php");
include_once("./models/HabitModel.php");
include_once("./models/hotelModel.php");
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
    public function mostrarInicio()
    {
        $this->habitView->mostrarInicio();
        //recibo el id del hotel
        if (isset($_POST['id_hotel'])) {
            $id_hotel = $_POST['id_hotel'];
        } else {
            $this->logController->logError('error al recuperar el id del hotel');
        }
        //tengo que obtener las habitaciones
        $habitaciones = $this->habitModel->getHabitaciones($id_hotel);
        //llamo al metodo que muestra las habitaciones
        $this->mostrarHabitaciones($habitaciones);
    }
    public function mostrarHabitaciones($habitaciones)
    {
        $this->habitView->mostrarHabitaciones($habitaciones);
    }

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
    public function mostrarFormularioMod($hotel)
    {
        $this->modView->mostrarFormularioMod($hotel);
    }

    //funcion para procesar el formulario de modificacion
    public function recibirFormularioMod()
    {
        //     TEMA DE LA FOTO
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $fotoHotel = $_FILES['foto']['name'];
            $rutaTempHotel = $_FILES['foto']['tmp_name'];
            $directorioFotos = __DIR__ . '/../assets/images/fotohoteles/';
            $rutaDestinoHotel = $directorioFotos . $fotoHotel;

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
            $this->hotelController->inicioHoteles();
        } else {
            var_dump($_POST);
            echo 'error al recibir los datos del formulario';
        }
    }
}
