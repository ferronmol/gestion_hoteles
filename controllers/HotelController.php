<?php
include_once("./views/baseView.php");
include_once("./views/hotelView.php");
include_once("./models/HotelModel.php");
include_once("./controllers/LogController.php");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
} else {
    // El usuario no está autenticado, redirigir al formulario de inicio de sesión
    header('Location: index.php?controller=User&action=mostrarInicio');
};
class HotelController
{
    private $baseView; //objeto de la clase BaseView
    private $hotelView; //objeto de la clase Login_formview
    private $HotelModel; //objeto de la clase UserModel
    private $logController; //objeto de la clase LogController

    public function __construct()
    {
        $this->baseView = new BaseView(); //crea un objeto de la clase BaseView
        $this->hotelView = new hotelView();  //crea un objeto de la clase Login_formview
        $this->logController = new LogController();
        $this->HotelModel = new HotelModel(new DB());
    }

    public function inicioHoteles()
    {
        // Verificar si el nombre de usuario está almacenado en la sesión
        if (isset($_SESSION['usuario']) && $_SESSION['usuario'] !== null) {
            //informo al logcontroler usando logAccess
            $this->logController->logAccess($_SESSION['usuario']->getNombre(), $_SESSION['usuario']->getRol());
            //gestion de cookie de ultima visita
            $fechaUltVisita = date('Y-m-d H:i:s');
            setcookie('ultima_visita', $fechaUltVisita, time() + 7 * 24 * 60 * 60, '/'); //valida por 7 dias
            // El usuario está autenticado, mostrar la página protegida
            $this->hotelView->inicioHoteles();
            //obtener y listar hoteles
            $hoteles = $this->obtenerHoteles();
            $this->listarHoteles($hoteles);
        } else {
            $this->baseView->setMensajeError("No tienes permisos para acceder a esta página");
            //log de error
            $this->logController->logError('error al acceder a la pagina de hoteles- no hay usuario logueado');
        }
    }

    public function obtenerHoteles()
    {
        if (isset($_SESSION['usuario']) && $_SESSION['usuario'] !== null) {
            $hoteles = $this->HotelModel->cogerHoteles();
            $this->listarHoteles($hoteles);
            //var_dump($hoteles);
        } else {
            $this->logController->logError('error al obtener los hoteles');
            $this->baseView->setMensajeError('No has podido obtener la lista de hoteles');
            return null;
        }
    }

    public function listarHoteles($hoteles)
    {
        try {
            if (isset($_SESSION['usuario']) && $_SESSION['usuario'] !== null) {
                $this->hotelView->mostrarHoteles($hoteles);
            } else {
                $this->baseView->setMensajeError("No puedo listar los hoteles");
            }
        } catch (Exception $e) {
            $this->logController->logError($e->getMessage());
        }
    }
}
