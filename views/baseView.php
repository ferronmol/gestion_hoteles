<?php
class baseView
{
    //mostrar mensajes de exito o error
    private $mensajeError;
    private $mensajeExito;

    // Método para establecer un mensaje de éxito
    public function setMensajeExito($mensaje)
    {
        $this->mensajeExito = $mensaje;
    }

    // Método para establecer un mensaje de error
    public function setMensajeError($mensaje)
    {
        $this->mensajeError = $mensaje;
    }
    public function mostrarMensajes()
    {
        if ($this->mensajeError != "") {
            echo '<div class="alert alert-danger" role="alert">' . $this->mensajeError . '</div>';
        }
        if ($this->mensajeExito != "") {
            echo '<div class="alert alert-success" role="alert">' . $this->mensajeExito . '</div>';
        }
    }
}
