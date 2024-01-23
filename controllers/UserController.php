<?php
//include_once("./views/userView.php");

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
            }
            //La contraseña debe tener al menos 6 caracteres
            if (strlen($contraseña) < 6) {
                $this->userView->mostrarError("La contraseña debe tener al menos 6 caracteres");
                //volver a mostrar el formulario
                $this->userView->mostrarFormulario();
            }
            //la contraseña la encriptamos con sha256
            $contraseña = hash("sha256", $contraseña);
            //Inicializamos el modelo solo cuando es necesario
            $this->UserModel = new UserModel(new DB());
            if ($this->UserModel->verifyCredenciales($nombre, $contraseña)) {

                //llamo al metodo iniciar sesion
                $this->iniciarSesion($nombre);
                return;
            } else {
                $this->userView->mostrarError("Usuario o contraseña incorrectos");
                //volver a mostrar el formulario
                $this->userView->mostrarFormulario();
                return;
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
        // Puedes realizar acciones de depuración aquí, por ejemplo      
    }
    public function mostrarExito($exito)
    {
        $this->userView->mostrarExito($exito);
    }
    public function iniciarSesion($nombre)
    {
        // Iniciar la sesión si no existe una sesión ya iniciada

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        //creo una cookie con el nombre del usuario y la hora de inicio de sesion
        setcookie("login-nombre", $nombre, time() + 3600);

        // Guardar datos en la sesión, el nombre y la hora de inicio de sesión

        $_SESSION['login'] = $nombre;

        // Redireccionar a la página de inicio
        header('Location: index.php?controller=Hotel&action=mostrarHoteles');
    }
}
