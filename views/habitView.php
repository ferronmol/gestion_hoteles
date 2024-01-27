<?php
class habitView
{
    public function mostrarInicio()
    {
        echo '<div class="main-container__rooms">';
        echo '<div class="main-container__content__title">';
        echo '<h1 class="animate-character">Ferron Hotels</h1>';
        echo '</div>';
        echo '<a href="index.php?controller=Hotel&action=inicioHoteles" class="btn btn-primary">Back</a>';
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

    public function mostrarHabitaciones($habitaciones)
    {
        echo '<div class="container mt-4 ml-12">';
        echo '<div class="rowleft row">';
        if (isset($habitaciones) && is_array($habitaciones)) {
            foreach ($habitaciones as $habitacion) { //$hoteles es un array con objetos hotel dentro(stdClass)
                echo '<div class="col-md-6 mb-6">';
                echo '<div class="card cardm">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $habitacion->getTipo() . '</h5>';
                echo '<p class="card-text">' . $habitacion->getDescripcion() . '</p>';

                echo '<ul class="list-group list-group-flush">';
                echo '<li class="list-group-item"><strong>Dirección:</strong> ' . $habitacion->getNum_Habitacion() . '</li>';
                echo '<li class="list-group-item"><strong>Ciudad:</strong> ' . $habitacion->getPrecio() . '</li>';
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
    }
}
