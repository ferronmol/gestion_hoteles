<?php
include_once("./controllers/LogController.php");
include_once("./controllers/HotelController.php");
include_once("./controllers/GestController.php");
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
    public function mostrarInicio()
    {
        //primero debo obtener las reservas
        $reservas =  $this->ReserModel->getReserva();
        //var_dump($reservas);
        //llamo a la vista para mostar las reservas
        $this->listarReservas($reservas);
        $this->reserView->mostrarInicio();
    }

    public function listarReservas($reservas)
    {
        $this->reserView->mostrarReservas($reservas);
    }
    public function modificarReserva()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $reservaId = $this->ReserModel->getById($id);
            $this->reserView->mostrarFormularioMod($reservaId);
        }
    }
}
