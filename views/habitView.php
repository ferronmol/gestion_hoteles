<?php
/*
*
* Clase habitView para mostrar las habitaciones de un hotel en la vista
* @param string $habitacionesOutput Es una inyección de código HTML
*/
class habitView extends baseView
{
    private $habitacionesOutput = '';

    /*
    * Muestra la página de inicio de la aplicación
    */

    public function mostrarInicio()
    {
        echo '<div class="main-container__rooms">';
        echo '<div class="main-container__content__title">';
        echo '<h1 class="animate-character">Ferron Hotels</h1>';
        echo '</div>';
        echo '<a href="index.php?controller=Hotel&action=inicioHoteles" class="btn btn-primary">Back</a>';
        echo $this->habitacionesOutput;
        echo '</div>';
    }

    /*
    * Muestra el listado de habitaciones de un hotel
    * @param array $habitaciones Es un array de objetos habitación
    * @param string $ciudad Es el nombre de la ciudad del hotel
    */
    public function listarHabitaciones($habitaciones, $ciudad)
    {
        //var_dump($habitaciones);
        ob_start();
        echo '<div class="container mt-4 ml-12">';
        echo '<h3 class="text-center white">Habitaciones en: ' . $ciudad . '</h3>"';
        echo '<div class="rowleft row">';
        if (isset($habitaciones) && is_array($habitaciones)) {
            foreach ($habitaciones as $habitacion) { //$hoteles es un array con objetos hotel dentro(stdClass)
                echo '<div class="col-lg-3 col-md-4 col-sm-6">';
                echo '<div class="card cardm">';
                echo '<div class="card-body">';
                echo '<h3 class="card-title white"><strong>ID: </strong>' . $habitacion->getId() . '</h3>';
                echo '<h3 class="card-subtitle whitexl"><strong>Tipo: </strong>' . $habitacion->getTipo() . '</h3>';
                echo '<p class="card-text white"><strong>Descripción: </strong>' . $habitacion->getDescripcion() . '</p>';

                echo '<ul class="list-group list-group-flush bg-custom">';
                echo '<li class="list-group-item bg-custom"><strong>NºHabitacion: </strong> ' . $habitacion->getNum_Habitacion() . '</li>';
                echo '<li class="list-group-item bg-custom"><strong>Precio: </strong> ' . $habitacion->getPrecio() . ' €</li>';
                echo '</ul>';
                if ($_SESSION['usuario']->getRol() == 1) {
                    // Si el rol es 1 (Administrador), mostrar los botones
                    echo '<div class="buttons-container mt-3 d-flex justify-content-around ">';
                    //creo un form para enviar el id del hotel a MODIFICAR
                    echo '<form method="post" action="index.php?controller=Gest&action=obtenerHabitacionPorId">';
                    echo '<input type="hidden" name="id" value="' . $habitacion->getId() . '">';
                    echo '<button class="btn btn-primary">Modificar</button>';
                    echo '</form>';
                    //creo un form para enviar el id del hotel y el num de la habitación a RESERVAS
                    echo '<form method="post" action="index.php?controller=Reser&action=mostrarInicio">';
                    echo '<input type="hidden" name="id" value="' . $habitacion->getId() . '">';
                    echo '<input type="hidden" name="id_hotel" value="' . $habitacion->getNum_Habitacion() . '">';
                    echo '<button class="btn btn-success">Reservas</button>';
                    echo '</form>';
                    //creo un form para enviar el id de la habitacion  a BORRAR
                    echo '<form method="post" action="index.php?controller=Gest&action=eliminarHabitacion">';
                    echo '<input type="hidden" name="id" value="' . $habitacion->getId() . '">';
                    echo '<button type="submit" class="btn btn-danger">Borrar</button>';
                    echo '</form>';
                    echo '</div>'; // Fin buttons-container
                }

                echo '</div>'; // Fin card-body
                echo '</div>'; // Fin card
                echo '</div>'; // Fin col-md-6
            }
        }
        echo '</div>'; // Fin row
        echo '</div>'; // Fin container
        $this->habitacionesOutput = ob_get_clean();
    }
}
