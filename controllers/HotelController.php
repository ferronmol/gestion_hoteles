<?php
include_once("./views/hotelView.php");
include_once("./models/HotelModel.php");
include_once("./controllers/LogController.php");
//si no esta iniciada la sesion te manda al login
if (session_status() == PHP_SESSION_NONE) {
    session_start();
} else {
    // El usuario no está autenticado, redirigir al formulario de inicio de sesión
    header('Location: index.php?controller=User&action=mostrarInicio');
};

class HotelController
{

    private $hotelView; //objeto de la clase Login_formview
    private $HotelModel; //objeto de la clase UserModel
    private $logController;

    public function __construct()
    {
        $this->hotelView = new hotelView();  //crea un objeto de la clase Login_formview
        $this->logController = new LogController();
    }

    public function inicioHoteles()
    {
        // Verificar si el nombre de usuario está almacenado en la sesión
        if (isset($_SESSION['usuario']) && $_SESSION['usuario'] !== null) {
            //informo al logcontroler usando logAccess
            $this->logController->logAccess($_SESSION['usuario']->getNombre(), $_SESSION['usuario']->getRol());
            // El usuario está autenticado, mostrar la página protegida
            $this->hotelView->inicioHoteles();
        } else {
            // Si no existe o es null, muestra un mensaje alternativo
            header('Location: index.php?controller=User&action=mostrarInicio');
        }
    }
}
