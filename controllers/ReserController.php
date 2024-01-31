<?php
include_once("./controllers/LogController.php");
include_once("./controllers/HotelController.php");
//include_once("./controllers/GestController.php");
include_once("./views/baseView.php");
include_once("./views/reserView.php");
include_once("./views/habitView.php");
include_once("./views/modView.php");
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
    private $ReserModel;
    private $reserView;

    public function __construct()
    {
        $this->logController = new LogController();
        $this->hotelController = new HotelController();
        $this->gestController = new GestController();
        $this->ReserModel = new ReserModel(new DB());
        $this->reserView = new reserView();
    }
    /*
    *Metodo para controlar la llamada a la pagina de inicio de reservas
    */
    public function mostrarInicio()
    {
        //primero debo obtener las reservas
        $reservas =  $this->ReserModel->getReserva();
        //var_dump($reservas);
        //llamo a la vista para mostar las reservas
        $this->listarReservas($reservas);
        $this->reserView->mostrarInicio();
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
    *Metodo para eliminar una reserva
    */
    public function modificarReserva()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $reservaId = $this->ReserModel->getById($id);
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
            $this->ReserModel->modificarReserva($reserva);
            $this->reserView->setMensajeExito("Reserva modificada con exito");
            $this->logController->logMod('reserva modificada', $id);
            $this->reserView->mostrarMensajes();
            $reservasHTML = $this->reserView->mostrarReservas($this->ReserModel->getReserva());
            echo $reservasHTML;
        }
    }
}
