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
            $nombre = $_POST['nombre'];
            $password = $_POST['password'];

            //validar los datos
            //El nombre no puede estar  vacio ni contener caracteres especiales ni numeros
            if (empty($nombre) || preg_match("/[0-9]/", $nombre) || preg_match("/[#$%&]/", $nombre)) {
                $this->userView->mostrarError("No me la lies con el nombre");
                //volver a mostrar el formulario
                $this->userView->mostrarFormulario();
                return;
            }
            //La contraseña debe tener al menos 6 caracteres
            if (strlen($password) < 6) {
                $this->userView->mostrarError("La contraseña debe tener al menos 6 caracteres");
                //volver a mostrar el formulario
                $this->userView->mostrarFormulario();
                return;
            }
            //la contraseña la encriptamos con sha256
            $password = password_hash($password, PASSWORD_DEFAULT);

            //Inicializamos el modelo solo cuando es necesario
            $this->UserModel = new UserModel(new DB());
            if ($this->UserModel->verifyCredenciales($nombre, $password)) {
                $usuario = new Usuario("", $nombre, $password, "", "");
                echo "Usuario autentificado";
            } else {
                $this->userView->mostrarError("Usuario o contraseña incorrectos");
                //volver a mostrar el formulario
                $this->userView->mostrarFormulario();
            }
        } catch (PDOException $e) {
            $this->userView->mostrarError("Error al conectar con la base de datos");
        }
    }
    public function mostrarError($mensaje)
    {
        $this->userView->mostrarError($mensaje);
    }
}
