<?php
include_once("./views/baseView.php");
include_once("./controllers/LogController.php");
include_once("./views/userView.php");

class UserController
{
    private $baseView; //objeto de la clase BaseView
    private $userView; //objeto de la clase Login_formview
    private $UserModel; //objeto de la clase UserModel
    private $logController; //objeto de la clase LogController

    /**
     * Constructor de la clase UserController.
     */
    public function __construct()
    {
        $this->userView = new userView();  //crea un objeto de la clase Login_formview
        $this->logController = new LogController();
        $this->baseView = new BaseView(); //crea un objeto de la clase BaseView
    }

    /**
     * Muestra la página de inicio.
     */
    public function mostrarInicio()
    {
        $this->userView->mostrarInicio();
    }

    /**
     * Muestra el formulario de inicio de sesión.
     */
    public function mostrarFormulario()
    {
        $this->userView->mostrarFormulario();
    }

    /**
     * Procesa el formulario de inicio de sesión.
     */
    public function procesarFormulario()
    {
        try {
            include_once("./models/UserModel.php");
            // Recoge el nombre y la contraseña, convierte el nombre a minúsculas
            $nombre = strtolower(htmlspecialchars($_POST['nombre']));
            $contraseña = htmlspecialchars($_POST['contraseña']);
            // Validar los datos
            // El nombre no puede estar vacío ni contener caracteres especiales ni números
            if (empty($nombre) || preg_match("/[0-9]/", $nombre) || preg_match("/[#$%&]/", $nombre)) {
                $this->baseView->setMensajeError("No me la lies con el nombre");
                $this->logController->logFailedAccess($nombre);
            }
            // La contraseña debe tener al menos 6 caracteres
            else if (strlen($contraseña) < 6) {
                $this->baseView->setMensajeError("La contraseña debe tener al menos 6 caracteres");
                $this->logController->logFailedAccess($nombre);
            } else {
                // La contraseña se encripta con sha256
                $contraseña = hash("sha256", $contraseña);
                // Inicializamos el modelo solo cuando es necesario
                $this->UserModel = new UserModel(new DB());
                if ($this->UserModel->verifyCredenciales($nombre, $contraseña)) {
                    // Llama al método iniciar sesión
                    $this->iniciarSesion($nombre);
                } else {
                    // Anota en el log
                    $this->logController->logFailedAccess($nombre);
                    $this->baseView->setMensajeError("Usuario o contraseña incorrectos");
                    $this->userView->mostrarFormulario();
                }
            }
            // Mostrar mensajes y volver a mostrar el formulario si es necesario
            $this->baseView->mostrarMensajes();
            $this->userView->mostrarFormulario();
        } catch (PDOException $e) { //la excecpcio del PDO del modelo
            $this->logController->logError($e->getMessage());
            $this->baseView->setMensajeError($e->getMessage());
            $this->baseView->mostrarMensajes();
            $this->userView->mostrarInicio();
        } catch (Exception $generalException) {
            // Manejar otras excepciones generales aquí
            $this->baseView->setMensajeError($generalException->getMessage());
        }
    }

    /**
     * Inicia la sesión del usuario.
     * @param string $nombre Nombre de usuario.
     */
    public function iniciarSesion($nombre)
    {

        $usuario = $this->UserModel->getUsuario($nombre);
        //guardo el usuario en la sesion
        $_SESSION['usuario'] = $usuario;

        header('Location: index.php?controller=Hotel&action=inicioHoteles');
        exit();
    }

    /**
     * Cierra la sesión del usuario, escribe en el log, destruye la sesión y redirige al índice.
     */
    public function cerrarSesion()
    {
        //informo al logcontroler usando logOut
        $this->logController->logOut($_SESSION['usuario']->getNombre(), $_SESSION['usuario']->getRol());
        //borro la sesion
        session_destroy();
        //vuelvo al index
        header('Location: index.php?controller=User&action=mostrarInicio');
        exit();
    }
}
