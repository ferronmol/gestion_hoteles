<?php
include_once("./views/userView.php");


class UserController
{

    private $userView; //objeto de la clase Login_formview
    private $UserModel; //objeto de la clase UserModel

    public function __construct()
    {
        $this->userView = new userView();  //crea un objeto de la clase Login_formview
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
            $nombre = strtolower($_POST['nombre']);
            $contraseña = $_POST['contraseña'];

            //validar los datos
            //El nombre no puede estar  vacio ni contener caracteres especiales ni numeros
            if (empty($nombre) || preg_match("/[0-9]/", $nombre) || preg_match("/[#$%&]/", $nombre)) {
                $this->userView->mostrarError("No me la lies con el nombre");
                //volver a mostrar el formulario
                $this->userView->mostrarFormulario();
                return;
            }
            //La contraseña debe tener al menos 6 caracteres
            if (strlen($contraseña) < 6) {
                $this->userView->mostrarError("La contraseña debe tener al menos 6 caracteres");
                //volver a mostrar el formulario
                $this->userView->mostrarFormulario();
                return;
            }
            //la contraseña la encriptamos con sha256
            $contraseña = hash("sha256", $contraseña);
            //Inicializamos el modelo solo cuando es necesario
            var_dump($contraseña);
            $this->UserModel = new UserModel(new DB());
            if ($this->UserModel->verifyCredenciales($nombre, $contraseña)) {
                $usuario = new Usuario("", $nombre, $contraseña, "", "");
                //Iniciamos la sesion y guardamos el usuario en la sesion
                $this->iniciarSesion($usuario);
            } else {
                $this->userView->mostrarError("Usuario o contraseña incorrectos");
                //volver a mostrar el formulario
                $this->userView->mostrarFormulario();
            }
        } catch (PDOException $e) {
            $this->userView->mostrarError($e->getMessage());
        } catch (Exception $generalException) {
            // Manejar otras excepciones generales aquí
            $this->userView->mostrarError($generalException->getMessage());
        }
    }
    public function mostrarError($mensaje)
    {
        $this->userView->mostrarError($mensaje);
    }
    public function mostrarExito($exito)
    {
        $this->userView->mostrarExito($exito);
    }
    public function iniciarSesion($usuario)
    {
        session_start();
        $_SESSION['usuario'] = $usuario;
        //llamo al controlador de log para que guarde el log
        include_once("./controllers/LogController.php");
        $logController = new LogController();
        $logController->logAccess($usuario->getNombre(), $usuario->getRol());
        //mando al usurio a la pagina de hoteles
        header("Location: ./index.php?controller=Hotel&action=mostrarHoteles");
    }
}
