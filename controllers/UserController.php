<?php
include_once("./views/UserView.php");
include_once("./models/UserModel.php");

class UserController
{

    //obtener una instancia del modelo y de la vista
    private $model;
    private $view;
    public function __construct(DB $db)
    {
        $this->model = new UserModel($db);
        $this->view = new UserView();
    }
}
