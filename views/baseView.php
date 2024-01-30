<?php

/**
 * Clase baseView
 * 
 * Esta clase proporciona funciones para mostrar mensajes de éxito y error en la interfaz de usuario.
 */
class baseView
{
    /**
     * @var string|null $mensajeError Mensaje de error a mostrar.
     */
    protected $mensajeError;

    /**
     * @var string|null $mensajeExito Mensaje de éxito a mostrar.
     */
    protected $mensajeExito;

    /**
     * Establece un mensaje de éxito.
     * 
     * @param string $mensaje El mensaje de éxito a establecer.
     * @return void
     */
    public function setMensajeExito($mensaje)
    {
        $this->mensajeExito = $mensaje;
    }

    /**
     * Establece un mensaje de error.
     * 
     * @param string $mensaje El mensaje de error a establecer.
     * @return void
     */
    public function setMensajeError($mensaje)
    {
        $this->mensajeError = $mensaje;
    }

    /**
     * Muestra los mensajes en la interfaz de usuario.
     * 
     * Imprime mensajes de error y éxito en la interfaz de usuario.
     * @return void
     */
    public function mostrarMensajes()
    {
        if ($this->mensajeError != "") {
            echo '<div class="alert alert-danger space auto-dismiss " role="alert" id="mensajeError">' . $this->mensajeError . '</div>';
        }
        if ($this->mensajeExito != "") {
            echo '<div class="alert alert-success space auto-dismiss-error" role="alert" id="mensajeExito">' . $this->mensajeExito . '</div>';
        }
    }
    public function getMensajeError()
    {
        return $this->mensajeError;
    }
    public function getMensajeExito()
    {
        return $this->mensajeExito;
    }
}
