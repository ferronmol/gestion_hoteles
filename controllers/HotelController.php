<?php
include_once("./views/hotelView.php");

class HotelController
{

    private $hotelView; //objeto de la clase Login_formview
    private $HotelModel; //objeto de la clase UserModel

    public function __construct()
    {
        $this->hotelView = new hotelView();  //crea un objeto de la clase Login_formview
    }

    public function mostrarHoteles()
    {
        $this->hotelView->mostrarHoteles();
    }
}
