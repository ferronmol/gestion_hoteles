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
    // private $habitModel;

    public function __construct()
    {
        $this->logController = new LogController();
        $this->habitView = new HabitView();
        // $this->habitModel = new HabitModel(new DB());
    }
    public function mostrarInicio()
    {
        $this->habitView->mostrarInicio();
    }
}
