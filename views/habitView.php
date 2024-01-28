<?php
class habitView
{
    private $habitacionesOutput = '';

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
    // Muestra un mensaje de error
    public function mostrarError($mensaje)
    {
        echo '<div class="space-top alert alert-danger" role="alert"><span class="medium">' . htmlspecialchars($mensaje) . '</span></div>';
    }
    // Muestra un mensaje de éxito
    public function mostrarExito($mensaje)
    {
        echo '<div class="alert alert-success" role="alert"> ' . htmlspecialchars($mensaje) . '</div>';
    }

    public function listarHabitaciones($habitaciones)
    {
        //var_dump($habitaciones);
        ob_start();
        echo '<div class="container mt-4 ml-12">';
        echo '<div class="rowleft row">';
        if (isset($habitaciones) && is_array($habitaciones)) {
            foreach ($habitaciones as $habitacion) { //$hoteles es un array con objetos hotel dentro(stdClass)
                echo '<div class="col-md-6 mb-6">';
                echo '<div class="card cardm">';
                echo '<div class="card-body">';
                echo '<h3 class="card-title"><strong>ID: </strong>' . $habitacion->getId() . '</h3>';
                echo '<h3 class="card-subtitle"><strong>Tipo: </strong>' . $habitacion->getTipo() . '</h3>';
                echo '<p class="card-text"><strong>Descripción: </strong>' . $habitacion->getDescripcion() . '</p>';

                echo '<ul class="list-group list-group-flush">';
                echo '<li class="list-group-item"><strong>NºHabitacion: </strong> ' . $habitacion->getNum_Habitacion() . '</li>';
                echo '<li class="list-group-item"><strong>Precio: </strong> ' . $habitacion->getPrecio() . '</li>';
                echo '</ul>';
                if ($_SESSION['usuario']->getRol() == 1) {
                    // Si el rol es 1 (Administrador), mostrar los botones
                    echo '<div class="buttons-container mt-3 d-flex justify-content-around ">';
                    echo '<button class="btn btn-primary">Modificar</button>';
                    echo '<a href="index.php?controller=Gest&action=mostrarReservas" class="btn btn-success">Reservas</a>';
                    echo '<button class="btn btn-danger">Borrar</button>';
                    echo '</div>';
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
