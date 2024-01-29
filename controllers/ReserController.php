<?php
include_once("./controllers/LogController.php");
include_once("./controllers/HotelController.php");
include_once("./views/baseView.php");
include_once("./views/habitView.php");
include_once("./views/hotelView.php");
include_once("./views/reserView.php");
include_once("./views/modView.php");
include_once("./models/ReserModel.php");
include_once("./models/HabitModel.php");
include_once("./models/hotelModel.php");
include_once("./config/Config.php");

if (!isset($_SESSION)) {
    header('Location: index.php?controller=User&action=mostrarInicio');
    exit();
}
class ReserController
{
    private $logController;
    private $hotelController;
    private $ReserModel;
    private $reserView;

    public function __construct()
    {
        $this->logController = new LogController();
        $this->hotelController = new HotelController();
        $this->ReserModel = new ReserModel(new DB());
        $this->reserView = new reserView();
    }
    public function mostrarInicio()
    {
        //primero debo obtener las reservas
        $reservas =  $this->ReserModel->getReserva();
        var_dump($reservas);
        //llamo a la vista para mostar las reservas
        $this->reserView->mostrarInicio();
    }
}
