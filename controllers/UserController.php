<?php
include_once("./views/Login_formview.php");
class UserController
{

    private $Login_formview; //objeto de la clase Login_formview

    public function __construct()
    {
        $this->Login_formview = new Login_formview();  //crea un objeto de la clase Login_formview
    }

    public function mostrarInicio()
    {
        $this->Login_formview->mostrarInicio();
    }





    public function mostrarFormulario()
    {
        $this->Login_formview->mostrarFormulario();
    }
}
