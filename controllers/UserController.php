<?php
include_once("./views/UserView.php");
include_once("./views/Login_formview.php");
//include_once("./models/UserModel.php");

class UserController
{

    //obtener una instancia del modelo y de la vista con dos atributos privados
    private $model;
    private $loginView;
    private $userView;

    public function __construct()
    {
        // $this->model = new UserModel($db);
        $this->loginView = new Login_formview;
        $this->userView = new UserView;
    }

    public function mostrarFormulario()
    {
        $this->loginView->mostrarFormulario();
    }


    public function mostrarInicio()
    {
        ob_start(); //almacena en memoria el contenido del buffer
        echo '<div class="main-container__content">';
        echo '<div class="main-container__content__title">';
        echo '<h1 class="animate-character">Ferron Hotels</h1>';
        echo '</div>';
        echo '<div class="main-container__content__subtitle">';
        echo '<h2 class="text txt-white">Where luxury meets comfort</h2>';
        echo '</div>';
        echo '<div class="main-container__content__btn">';
        echo '<form action="../frontcontroller.php" method="get">';
        echo ' <input type="hidden" name="action" value="mostrarformulario">';
        echo '<button type="submit" class="btn-entrar" id="btn-entrar"><span>Entrar</span></button>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        $contenido = ob_get_clean(); //obtiene el contenido del buffer y lo almacena en la variable
        $this->userView->setMainContent($contenido);
    }
}
