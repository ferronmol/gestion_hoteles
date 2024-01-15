<?php
class Userview
{

    //voy a dividir la vista en 3 partes, el header, el main y el footer

    private $headerContent;
    private $mainContent;
    private $footerContent;

    //mostrar el header
    public function setHeaderContent($content)
    {
        $this->headerContent = $content;
    }
    //mostrar el main
    public function setMainContent($content)
    {
        $this->mainContent = $content;
    }
    public function getMainContent()
    {
        return $this->mainContent;
    }

    //mostrar el footer
    public function setFooterContent($content)
    {
        $this->footerContent = $content;
    }

    public function __construct() //inicio las variables
    {
        $this->headerContent = '';
        $this->mainContent = '';
        $this->footerContent = '';
    }
}
