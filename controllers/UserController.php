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

    public function __construct()
    {
        $this->userView = new userView();  //crea un objeto de la clase Login_formview
        $this->logController = new LogController();
        $this->baseView = new BaseView(); //crea un objeto de la clase BaseView
    }

    public function mostrarInicio()
    {
        $this->userView->mostrarInicio();
    }


    public function mostrarFormulario()
    {
        $this->userView->mostrarFormulario();
    }

    public function procesarFormulario()
    {
        try {
            include_once("./models/UserModel.php");
            //RECOGE EL NOMBRE Y LA CONTRASEÑA  , el nombre a minusculas
            $nombre = strtolower(htmlspecialchars($_POST['nombre']));
            $contraseña = htmlspecialchars($_POST['contraseña']);
            //validar los datos
            //El nombre no puede estar  vacio ni contener caracteres especiales ni numeros
            if (empty($nombre) || preg_match("/[0-9]/", $nombre) || preg_match("/[#$%&]/", $nombre)) {
                $this->baseView->setMensajeError("No me la lies con el nombre");
                $this->baseView->mostrarMensajes();
                //volver a mostrar el formulario
                $this->userView->mostrarFormulario();
            }
            //La contraseña debe tener al menos 6 caracteres
            else if (strlen($contraseña) < 6) {
                $this->baseView->setMensajeError("La contraseña debe tener al menos 6 caracteres");
                $this->baseView->mostrarMensajes();
                //volver a mostrar el formulario
                $this->userView->mostrarFormulario();
            } else {
                //la contraseña la encriptamos con sha256
                $contraseña = hash("sha256", $contraseña);
                //Inicializamos el modelo solo cuando es necesario
                $this->UserModel = new UserModel(new DB());
                if ($this->UserModel->verifyCredenciales($nombre, $contraseña)) {

                    //llamo al metodo iniciar sesion
                    $this->iniciarSesion($nombre);
                } else {
                    //lo anoto en el log
                    $this->logController->logFailedAccess($nombre);
                    $this->baseView->setMensajeError("Usuario o contraseña incorrectos");
                    $this->baseView->mostrarMensajes();
                    $this->userView->mostrarFormulario();
                }
            }
        } catch (PDOException $e) {
            $this->baseView->setMensajeError($e->getMessage());
            $this->baseView->mostrarMensajes();
        } catch (Exception $generalException) {
            // Manejar otras excepciones generales aquí
            $this->baseView->setMensajeError($generalException->getMessage());
        }
    }


    public function iniciarSesion($nombre)
    {

        $usuario = $this->UserModel->getUsuario($nombre);
        //guardo el usuario en la sesion
        $_SESSION['usuario'] = $usuario;

        header('Location: index.php?controller=Hotel&action=inicioHoteles');
        exit();
    }

    //funcion para cerrar la sesion, escribir el log, borrar la sesion y volver al index
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
