<?php
include_once("./controllers/LogController.php");
include_once("./views/habitView.php");
include_once("./models/HabitModel.php");
if (!isset($_SESSION)) {
    header('Location: index.php?controller=User&action=mostrarInicio');
    exit();
}

class GestController
{
    private $logController;
    private $habitView;
    private $habitModel;

    public function __construct()
    {
        $this->logController = new LogController();
        $this->habitView = new HabitView();
        $this->habitModel = new HabitModel(new DB());
    }
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
}
