<?php
include_once("./controllers/LogController.php");
include_once("./controllers/HotelController.php");
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
    private $userModel;
    private $hotelModel;
    private $habitModel;
    private $reserModel;
    private $reserView;
    private $esAdmin;
    private $idUsuarioAutenticado;
    private $hoteles;

    public function __construct()
    {
        $this->logController = new LogController();
        $this->hotelController = new HotelController();
        $this->userModel = new UserModel(new DB());
        $this->hotelModel = new HotelModel(new DB());
        $this->reserModel = new ReserModel(new DB());
        $this->habitModel = new HabitModel(new DB());
        $this->reserView = new reserView();
        $this->idUsuarioAutenticado = isset($_SESSION['usuario']) ? $_SESSION['usuario']->getId() : null;
        $this->esAdmin = $this->idUsuarioAutenticado ? ($_SESSION['usuario']->getRol() === 1) : false;
        $this->hoteles = $this->hotelModel->cogerHoteles();
        //var_dump($this->hoteles);
    }
    /*
    *Metodo para controlar la llamada a la pagina de inicio de reservas
    */
    public function mostrarInicio()
    {
        //primero debo obtener las reservas segun si es admin o usuario por lo que reupero el usuario de la sesion
        //var_dump($_SESSION);
        // Obtener el usuario desde la sesión
        $usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;

        // Obtener el ID del usuario autenticado
        $this->idUsuarioAutenticado = $usuario ? $usuario->getId() : null;

        //verificar si el usuario es admin
        $esAdmin = $usuario && $usuario->getRol() === 1;

        $reservas =  $this->reserModel->getAllReservas(null, $this->idUsuarioAutenticado);
        //var_dump($reservas);
        //voy a coger los hoteles porque lo necesito para el formulario de crear reserva

        $this->reserView->mostrarInicio($reservas, $esAdmin);
    }
    /*
   *Metodo para mostrar el formulario de reserva
   * @param aarray $reservas Array con los objetos Reserva a mostrar
   */
    public function listarReservas()
    {
        $usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;

        if ($usuario) {
            // Si hay un usuario en la sesión, obtener sus reservas
            $idUsuario = $usuario->getId();
            $rolUsuario = $usuario->getRol();

            if ($rolUsuario == 0) {
                // Usuario normal
                $reservas = $this->reserModel->getAllReservas(null, $idUsuario);
            } elseif ($rolUsuario == 1) {
                // Administrador
                if (isset($_POST['id']) && isset($_POST['id_hotel'])) {
                    // Obtener reservas de una habitación específica para el administrador
                    $habitacionId = htmlspecialchars($_POST['id']);
                    $hotelId = htmlspecialchars($_POST['id_hotel']);
                    $reservas = $this->reserModel->getAllReservas($habitacionId, null, $hotelId);
                } else {
                    // Obtener todas las reservas para el administrador
                    $reservas = $this->reserModel->getAllReservas();
                }
            }
        } else {
            // Si no hay usuario (por ejemplo, es un administrador), obtener todas las reservas
            $reservas = $this->reserModel->getAllReservas();
        }

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
            //lo meto en la session
            $_SESSION['reserva'] = $reservaId;
            // var_dump($reservaId);
            // die();
            //recupero las reservas de la session
            if (isset($_SESSION['reservas'])) {
                $reservas = $_SESSION['reservas'];
                //var_dump($reservas);
                //die();
                $this->reserView->mostrarFormularioMod($reservaId, $this->esAdmin, $reservas);
            } else {
                $reservas = $this->reserModel->getAllReservas();
                //var_dump($reservas);
                //die();
                $this->reserView->mostrarFormularioMod($reservaId, $this->esAdmin, $reservas);
            }
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
            $reservasHTML = $this->reserView->mostrarReservas($this->reserModel->getAllReservas());
            echo $reservasHTML;
        }
    }

    /**
     * Elimina una reserva.
     */
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

    /**
     * Realiza una reserva.
     */
    public function hacerReserva()
    {
        //Recupero el id del usuario de la sesion
        $id_usuario = $_SESSION['usuario']->getId();

        $this->reserView->mostrarFormularioCreate($this->esAdmin, $id_usuario, $this->hoteles);
    }

    /**
     * Procesa la creación de reservas.
     */
    public function procesarCreacionReservas()
    {
        if (isset($_POST['submit'])) {
            // Obtener datos del formulario
            $fecha_entrada = htmlspecialchars($_POST['fecha_entrada']);
            $fecha_salida = htmlspecialchars($_POST['fecha_salida']);
            $id_usuario = htmlspecialchars($_POST['id_usuario']);
            $id_hotel = htmlspecialchars($_POST['id_hotel']);
            $id_habitacion = htmlspecialchars($_POST['id_habitacion']);

            // var_dump($fecha_entrada, $fecha_salida, $id_usuario, $id_hotel, $id_habitacion);
            // Verificar si el usuario existe
            $usuarioExistente = $this->usuarioExiste($id_usuario);

            // Verificar si el hotel existe
            $hotelExistente = $this->hotelExiste($id_hotel);

            //verificar que l ahabitacion existe
            $habitacionExistente = $this->habitacionExiste($id_habitacion, $id_hotel);
            //var_dump($id_habitacion, $id_hotel, $habitacionExistente);
            // Verificar si la habitación existe y está disponible en las fechas seleccionadas
            $habitacionDisponible = $this->habitacionDisponible($id_habitacion, $fecha_entrada, $fecha_salida);
            // var_dump($usuarioExistente, $hotelExistente, $habitacionExistente, $habitacionDisponible);
            // die();
            // Procesar la reserva si todas las verificaciones son exitosas
            if ($usuarioExistente && $hotelExistente && $habitacionDisponible) {
                // Crear el objeto Reserva y guardarlo en la base de datos
                $reserva = new Reserva(null, $id_usuario, $id_hotel, $id_habitacion, $fecha_entrada, $fecha_salida);
                // var_dump($reserva);
                // die();
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
                $this->reserView->mostrarFormularioCreate($this->esAdmin, $this->idUsuarioAutenticado, $this->hoteles);
            }
        }
    }

    /**
     * Verifica si un usuario existe en la base de datos.
     *
     * @param int $id_usuario El ID del usuario a verificar.
     * @return bool Retorna true si el usuario existe, de lo contrario retorna false.
     */
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


    /**
     * Verifica si un hotel existe en la base de datos.
     *
     * @param int $id_hotel El ID del hotel a verificar.
     * @return bool Retorna true si el hotel existe, de lo contrario retorna false.
     */
    private function hotelExiste($id_hotel)
    {
        $hotelExiste = $this->hotelModel->hotelExiste($id_hotel);
        if ($hotelExiste) {
            return $hotelExiste;
        } else {
            $this->logController->logMod('El hotel para crear reserva no existe');
        }
    }

    /**
     * Verifica si una habitación existe.
     *
     * @param int $id_habitacion El ID de la habitación a verificar.
     * @return bool Retorna true si la habitación existe, de lo contrario retorna false.
     */
    private function habitacionExiste($id_habitacion, $id_hotel)
    {
        $habitacionExiste = $this->habitModel->habitacionExiste($id_habitacion, $id_hotel);
        // var_dump($habitacionExiste);
        // die();
        if ($habitacionExiste) {
            $this->logController->logMod('La habitación para crear reserva existe');
            return false;
        } else {
            $this->logController->logMod('La habitación  para crear reserva no existe');
            return true;
        }
    }


    /**
     * Verifica si una habitación está disponible en las fechas especificadas.
     *
     * @param int $id_habitacion El ID de la habitación a verificar.
     * @param string $fecha_entrada La fecha de entrada en formato 'YYYY-MM-DD'.
     * @param string $fecha_salida La fecha de salida en formato 'YYYY-MM-DD'.
     * @return bool Retorna true si la habitación está disponible, de lo contrario retorna false.
     */
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


    // Obtener el nombre del usuario por su ID
    private function cogerNombreUsuario($idUsuario)
    {
        $usuario = $this->userModel->cogerNombreUsuario($idUsuario);
        return $usuario;
    }

    // Obtener el nombre del hotel por su ID
    private function cogerNombreHotel($idHotel)
    {
        $hotel = $this->hotelModel->getHotelById($idHotel);
        $nombreHotel = $hotel->getNombre();
        return $nombreHotel;
    }

    // Obtener el número de la habitación por su ID
    private function cogerNumeroHabitacion($idHabitacion)
    {
        $habitacion = $this->habitModel->getHabitacionById($idHabitacion);
        $numeroHabitacion = $habitacion->getNum_habitacion();
        return $numeroHabitacion;
    }
}
