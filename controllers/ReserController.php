<?php
include_once("./controllers/LogController.php");
include_once("./controllers/HotelController.php");
//include_once("./controllers/GestController.php");
include_once("./views/baseView.php");
include_once("./views/reserView.php");
include_once("./views/habitView.php");
include_once("./views/modView.php");
include_once("./models/UserModel.php");
include_once("./models/HotelModel.php");
include_once("./models/HabitModel.php");
include_once("./models/ReserModel.php");
include_once("./config/Config.php");

if (!isset($_SESSION)) {
    header('Location: index.php?controller=User&action=mostrarInicio');
    exit();
}
class ReserController
{
    private $logController;
    private $hotelController;
    private $gestController;
    private $userModel;
    private $hotelModel;
    private $habitModel;
    private $reserModel;
    private $reserView;

    public function __construct()
    {
        $this->logController = new LogController();
        $this->hotelController = new HotelController();
        $this->gestController = new GestController();
        $this->userModel = new UserModel(new DB());
        $this->hotelModel = new HotelModel(new DB());
        $this->reserModel = new ReserModel(new DB());
        $this->habitModel = new HabitModel(new DB());
        $this->reserView = new reserView();
    }
    /*
    *Metodo para controlar la llamada a la pagina de inicio de reservas
    */
    public function mostrarInicio()
    {
        //primero debo obtener las reservas segun si es admin o usuario por lo que reupero el usuario de la sesion
        //var_dump($_SESSION);
        // Obtener el usuario desde la sesión (ajusta según tu implementación)
        $usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;

        // Obtener el ID del usuario autenticado
        $idUsuarioAutenticado = $usuario ? $usuario->getId() : null;
        //var_dump($idUsuarioAutenticado);

        $reservas =  $this->reserModel->getReserva($idUsuarioAutenticado);
        //var_dump($reservas);
        $this->reserView->mostrarInicio($reservas);
    }
    /*
   *Metodo para mostrar el formulario de reserva
   * @param aarray $reservas Array con los objetos Reserva a mostrar
   */
    public function listarReservas($reservas)
    {
        $this->reserView->mostrarReservas($reservas);
    }

    /*
    *Metodo para modificar una reserva
    */
    public function modificarReserva()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $reservaId = $this->reserModel->getById($id);
            $this->reserView->mostrarFormularioMod($reservaId);
        }
    }

    /*
    *Metodo para procesar el fomulario de modificacion de reserva
    */
    public function procesarReservas()
    {
        if (isset($_POST['submit'])) {
            $id = htmlspecialchars($_POST['id']);                       //id de la reserva PK
            $fecha_entrada = htmlspecialchars($_POST['fecha_entrada']); //fecha de entrada
            $fecha_salida = htmlspecialchars($_POST['fecha_salida']);   //fecha de salida
            $id_usuario = htmlspecialchars($_POST['id_usuario']);       //id del usuario PK
            $id_hotel = htmlspecialchars($_POST['id_hotel']);           //id del hotel PK
            $id_habitacion = htmlspecialchars($_POST['id_habitacion']); //id de la habitacion PK
            $reserva = new Reserva($id, $id_usuario, $id_hotel, $id_habitacion, $fecha_entrada, $fecha_salida);
            //var_dump($reserva);
            $this->reserModel->modificarReserva($reserva);
            $this->reserView->setMensajeExito("Reserva modificada con exito");
            $this->logController->logMod('reserva modificada', $id);
            $this->reserView->mostrarMensajes();
            $reservasHTML = $this->reserView->mostrarReservas($this->reserModel->getReserva());
            echo $reservasHTML;
        }
    }
    public function eliminarReserva()
    {
        if (isset($_GET['id'])) {
            $id = htmlspecialchars($_GET['id']);
            $this->reserModel->eliminarReserva($id);
            $this->logController->logMod('Borrado de la reserva: ', $id);
            $this->reserView->setMensajeExito('Reserva borrada con exito');
            $this->mostrarInicio();
        } else {
            $this->logController->logError('No se pudo borrar la reserva');
        }
    }
    public function hacerReserva()
    {

        // Lanzo el formulario de Creación de reserva
        $this->reserView->mostrarFormularioCreate();
    }
    public function procesarCreacionReservas()
    {
        if (isset($_POST['submit'])) {
            // Obtener datos del formulario
            $fecha_entrada = htmlspecialchars($_POST['fecha_entrada']);
            $fecha_salida = htmlspecialchars($_POST['fecha_salida']);
            $id_usuario = htmlspecialchars($_POST['id_usuario']);
            $id_hotel = htmlspecialchars($_POST['id_hotel']);
            $id_habitacion = htmlspecialchars($_POST['id_habitacion']);

            // Verificar si el usuario existe
            $usuarioExistente = $this->usuarioExiste($id_usuario);

            // Verificar si el hotel existe
            $hotelExistente = $this->hotelExiste($id_hotel);

            //verificar que l ahabitacion existe
            $habitacionExistente = $this->habitacionExiste($id_habitacion);

            // Verificar si la habitación existe y está disponible en las fechas seleccionadas
            $habitacionDisponible = $this->habitacionDisponible($id_habitacion, $fecha_entrada, $fecha_salida);

            // Procesar la reserva si todas las verificaciones son exitosas
            if ($usuarioExistente && $hotelExistente && $habitacionDisponible) {
                // Crear el objeto Reserva y guardarlo en la base de datos
                $reserva = new Reserva(null, $id_usuario, $id_hotel, $id_habitacion, $fecha_entrada, $fecha_salida);
                // var_dump($reserva);
                $this->reserModel->crearReserva($reserva);
                // Redirigir o mostrar un mensaje de éxito
                $this->reserView->setMensajeExito("Reserva creada con éxito");
                $this->reserView->mostrarMensajes();
                $this->mostrarInicio();
            } else {
                // Mostrar mensajes de error según el caso
                if (!$usuarioExistente) {
                    $this->reserView->setMensajeError("El usuario no existe");
                }
                if (!$hotelExistente) {
                    $this->reserView->setMensajeError("El hotel no existe");
                }
                if (!$habitacionExistente) {
                    $this->reserView->setMensajeError("La habitacion no existe");
                }
                if (!$habitacionDisponible) {
                    $this->reserView->setMensajeError("La habitación no está disponible en las fechas seleccionadas");
                }

                // Mostrar mensajes de error
                $this->reserView->mostrarMensajes();
                $this->reserView->mostrarFormularioCreate();
            }
        }
    }

    private function usuarioExiste($id_usuario)
    {
        $usuarioExistente = $this->userModel->usuarioExiste($id_usuario);

        // Verificar el resultado 
        if ($usuarioExistente) {
            return $usuarioExistente;
        } else {
            // El usuario no existe, muestro error
            $this->reserView->setMensajeError('El usuario no existe.');
            $this->reserView->mostrarMensajes();
        }
    }

    private function hotelExiste($id_hotel)
    {
        $hotelExiste = $this->hotelModel->hotelExiste($id_hotel);
        if ($hotelExiste) {
            return $hotelExiste;
        } else {
            $this->logController->logMod('El hotel para crear reserva no existe');
        }
    }
    private function habitacionExiste($id_habitacion)
    {
        $habitacionExiste = $this->habitModel->habitacionExiste($id_habitacion);
        if ($habitacionExiste) {
            return $habitacionExiste;
        } else {
            $this->logController->logMod('La habitación  para crear reserva no existe');
        }
    }


    private function habitacionDisponible($id_habitacion, $fecha_entrada, $fecha_salida)
    {
        $habitacionDisponible = $this->reserModel->habitacionDisponible($id_habitacion, $fecha_entrada, $fecha_salida);
        if ($habitacionDisponible) {
            return $habitacionDisponible;
        } else {
            $this->reserView->setMensajeError('En esas fechas imposible, ya estan ocupadas');
            $this->reserView->mostrarMensajes();
        }
    }
}
