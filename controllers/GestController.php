<?php
include_once("./controllers/LogController.php");
include_once("./controllers/HotelController.php");
include_once("./views/baseView.php");
include_once("./views/habitView.php");
include_once("./views/hotelView.php");
include_once("./views/modView.php");
include_once("./models/HabitModel.php");
include_once("./models/hotelModel.php");
include_once("./models/reserModel.php");
include_once("./config/Config.php");

if (!isset($_SESSION)) {
    header('Location: index.php?controller=User&action=mostrarInicio');
    exit();
}
/**
 * Clase GestController
 * @param object $baseView Es un objeto de la clase baseView
 * @param object $logController Es un objeto de la clase LogController
 * @param object $hotelController Es un objeto de la clase HotelController
 * @param object $habitView Es un objeto de la clase habitView
 * @param object $habitModel Es un objeto de la clase HabitModel
 * @param object $hotelModel Es un objeto de la clase HotelModel
 * @param object $reserModel Es un objeto de la clase ReserModel
 * @param object $modView Es un objeto de la clase ModView
 * @param object $hotelView Es un objeto de la clase HotelView
 */
class GestController
{
    private $baseView;
    private $logController;
    private $hotelController;
    private $habitView;
    private $habitModel;
    private $hotelModel;
    private $reserModel;
    private $modView;
    private $hotelView;


    public function __construct()
    {
        $this->baseView = new BaseView();
        $this->logController = new LogController();
        $this->hotelController = new HotelController();
        $this->habitView = new HabitView();
        $this->habitModel = new HabitModel(new DB());
        $this->hotelModel = new HotelModel(new DB());
        $this->reserModel = new ReserModel(new DB());
        $this->modView = new ModView();
        $this->hotelView = new HotelView();
    }
    /**
     * esat funcion es inicio de la visualizacion de habitaciones
     */

    public function mostrarHabitaciones()
    {
        //recibo el id del hotel
        if (isset($_POST['id_hotel'])) {
            $id = $_POST['id_hotel'];
            //con el id del hotel obtengo el objeto hotel
            $hotel = $this->hotelModel->getHotelById($id);
            //tengo que obtener las habitaciones del hotel seleccionado
            $habitaciones = $this->habitModel->getHabitaciones($hotel->getId());
            //llamo al metodo que muestra las habitaciones
            $this->listarHabitaciones($habitaciones, $hotel);
            $this->baseView->setMensajeExito('Habitaciónes del hotel en ' . $hotel->getCiudad() . ' listadas con éxito');
            $this->habitView->mostrarInicio(); //ok
        } else {
            $this->logController->logError('error al recuperar el hotel');
        }
    }
    /**
     * funcion para listar las habitaciones
     */

    public function listarHabitaciones($habitaciones, $hotel)
    {
        //var_dump($habitaciones);
        $this->habitView->listarHabitaciones($habitaciones, $hotel); //ok
    }
    /**
     * Método para mostrar el formulario de modificación de habitaciones 
     * @param Habitacion $habitacion
     * @return void
     */

    public function mostrarFormularioCrearHabitaciones()
    {
        //recibo el id del hotel
        if (isset($_POST['id_hotel'])) {
            $id = $_POST['id_hotel'];
            //con el id del hotel obtengo el objeto hotel
            $hotel = $this->hotelModel->getHotelById($id);
            $this->modView->CrearHabitaciones($hotel);
        } else {
            $this->logController->logError('error al recuperar el hotel');
        }
    }
    /************************OBTENER DATOS PARA RELLENAR FORM ***********************/
    /**
     * Método para obtener los datos de un hotel por su id
     */
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
    /**
     * Método para obtener los datos de una habitación por su id
     */
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
    /************************ MOSTRAR DATOS  PARA RELLENAR FORM ***********************/
    /**
     * Método para mostrar el formulario de modificación de hoteles
     */
    public function mostrarFormularioMod()
    {
        //recibo el nombre, ciudad e id del hotel
        if (isset($_POST['id']) && isset($_POST['nombre']) && isset($_POST['ciudad'])) {
            $id = htmlspecialchars($_POST['id']);
            $nombre = htmlspecialchars($_POST['nombre']);
            $ciudad = htmlspecialchars($_POST['ciudad']);
        } else {
            $this->logController->logError('error al recuperar el hotel');
        }
        //con el id del hotel obtengo el objeto hotel
        $hotel = $this->hotelModel->getHotelById($id);
        //var_dump($hotel);ok
        $this->modView->mostrarFormularioMod($hotel);
    }
    /**
     * Método para mostrar el formulario de modificación de habitaciones
     */

    public function mostrarFormularioModHabitacion($habitacion)
    {
        $this->modView->mostrarFormularioModHabitaciones($habitacion);
    }
    /*******************************PROCESAMIENTO DE  MODIFICACION DE FORMULARIOS******************************* */
    /**
     * Método para procesar el formulario de modificación de hoteles
     */
    public function recibirFormularioMod()
    {
        // Gestión de la foto del hotel
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $fotoHotel = $_FILES['foto']['name'];
            $rutaTempHotel = $_FILES['foto']['tmp_name'];
            $rutaFotos = __DIR__ . '/../assets/images/fotohoteles/';
            $rutaDestinoHotel = $rutaFotos . $fotoHotel;

            // Intentar mover el archivo
            if (move_uploaded_file($rutaTempHotel, $rutaDestinoHotel)) {
                $this->logController->logMod('Se ha modificado un hotel');
            } else {
                // Manejar error de carga de archivo
                $this->logController->logError('Error al cargar la foto del hotel');
                $fotoHotel = null;
            }
        } else {
            $fotoHotel = null;
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


            $this->hotelModel->modificarHotel($id, $nombre, $direccion, $ciudad, $pais, $num_habitaciones, $descripcion, $fotoHotel);
            // Establecer mensajes en la vista modView
            $this->modView->setMensajeExito('Hotel modificado con éxito');
            $this->logController->logMod('Se ha modificado un hotel');
            $hotel = $this->hotelModel->getHotelById($id);
            $this->modView->mostrarFormularioMod($hotel);
        } else {
            // Establecer mensajes de error en la vista modView
            $this->modView->setMensajeError('Error al recibir los datos del formulario de modificación');
        }
    }

    /**
     * funcion para procesar el formulario de modificacion DE HABITACIONES
     */

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
            //mensaje de exito
            $this->habitView->setMensajeExito('Habitación modificada con éxito');
            $this->logController->logMod('Se ha modificado una habitación');
            $this->habitView->mostrarMensajes();
            $habitacion = $this->habitModel->getHabitacionById($id_hotel);
            $this->modView->mostrarFormularioModHabitaciones($habitacion);
        } else {
            $this->logController->logError('error al recibir los datos del formulario');
            $this->baseView->setMensajeError('Error al recibir los datos del formulario');
            //var_dump($_POST);
        }
    }
    /*******************************FORMULARIOS DE CREACIÓN ******************************* */
    /**
     * Método para recibir el formulario de creación de habitaciones
     */
    public function recibirFormularioCrearhabitaciones()
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
                $this->logController->logError('Se ha intentado crear una habitación con el mismo número en el mismo hotel');
                $this->modView->setMensajeError('Ya existe una habitación con el mismo número en el mismo hotel');
                //vuelvo a mostrar el fomulario de modificacion de habitaciones
                $habitacion = $this->habitModel->getHabitacionById($id_hotel);
                $this->modView->mostrarFormularioModHabitaciones($habitacion);
            } else {
                //verifio la cantidad actual de habitaciones del hotel
                $habitacionesActuales = $this->habitModel->getHabitaciones($id_hotel);
                $cantidadHabitacionesActuales = count($habitacionesActuales);
                $cantidadMaxima = ($id_hotel == 1) ? 10 : (($id_hotel == 2) ? 20 : 0); //pongo los maximos de habitaciones por hotel

                if ($cantidadHabitacionesActuales >= $cantidadMaxima) {
                    $this->logController->logError('El hotel ya tiene el máximo de habitaciones');
                    $this->modView->setMensajeError('El hotel ya tiene el máximo de habitaciones');
                    $habitacion = $this->habitModel->getHabitacionById($id_hotel);
                    $this->modView->mostrarFormularioModHabitaciones($habitacion);
                    $this->modView->mostrarMensajes();
                } else {
                    $this->habitModel->crearHabitacion($id_hotel, $num_habitacion, $tipo, $precio, $descripcion);
                    $this->habitView->setMensajeExito('Habitación creada con éxito');
                    $this->habitView->mostrarMensajes();
                    $this->logController->logMod('Se ha creado una habitación');
                    $habitacion = $this->habitModel->getHabitacionById($id_hotel);
                    $this->modView->mostrarFormularioModHabitaciones($habitacion);
                }
            }
        } else {
            $this->logController->logError('error al recibir los datos del formulario');
            $this->baseView->setMensajeError('Error al recibir los datos del formulario');
            //var_dump($_POST);
        }
    }
    /******************************* MÉTODOS DE BORRADO ******************************* */
    /**
     * Método para borrar una habitacion
     * @param string $id Es el id de la habitacion
     */
    public function eliminarHabitacion()
    {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];

            // Verifica si hay reservas asociadas a la habitación
            if ($this->reserModel->getByIdHabitacion($id)) {
                $this->habitView->setMensajeError('No se puede borrar la habitación porque tiene reservas asociadas.');
            } else {
                $this->habitModel->eliminarHabitacion($id);
                $this->habitView->setMensajeExito('Habitación borrada con éxito');
                $this->logController->logMod('Se ha borrado una habitación');
            }

            $this->habitView->mostrarMensajes();
            $this->habitView->mostrarInicio();
        } else {
            $this->logController->logError('Error al recibir los datos del formulario');
            $this->baseView->setMensajeError('Error al recibir los datos del formulario');
        }
    }
    /**
     * Método para borrar un hotel
     */
    public function borrarHotel()
    {
        if (isset($_POST['id_hotel'])) {
            $id_hotel = $_POST['id_hotel'];
            $nombre = $_POST['nombre'];
            // Verifica si hay reservas asociadas al hotel
            if ($this->reserModel->getByIdHotel($id_hotel)) {
                $this->hotelView->setMensajeError('No se puede borrar el hotel porque tiene reservas asociadas.');
            } else {
                $this->hotelView->setMensajeExito('Hotel ' . $nombre . ' sin reservas asocidas, borrado posible)');
                $this->logController->logMod('Intento de borrado');
            }
            $this->hotelView->inicioHoteles();
        } else {
            $this->logController->logError('Error al recibir los datos del formulario');
            $this->baseView->setMensajeError('Error al recibir los datos del formulario');
        }
    }
}
