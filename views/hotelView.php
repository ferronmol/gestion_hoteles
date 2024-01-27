<?php
class hotelView
{
    public function inicioHoteles()
    {
        echo '<div class="main-container__content">';
        echo '<div class=checkout-container>';
        echo '<a href="index.php?controller=User&action=cerrarSesion" class="btn-salir"><span>Out Session</span></a>';
        echo '</div>';
        echo '<div class="block-container">';
        echo '<div>';
        // Información de usuario
        echo '<p class="text text--min">Usuario: ' . $_SESSION['usuario']->getNombre() . '</p>';
        echo '</div>';
        echo '<div>';
        // Rol del usuario
        if ($_SESSION['usuario']->getRol() == 1) {
            echo '<p class="text text--min">Administrador</p>';
        } else {
            echo '<p class="text text--min">Usuario</p>';
        }
        echo '</div>';
        echo '<div>';
        // Última visita
        if (isset($_COOKIE['ultima_visita'])) {
            $fechaUltimaVisita = urldecode($_COOKIE['ultima_visita']);
            echo '<p class="text text--min">Última visita: ' . $fechaUltimaVisita . '</p>';
        }
        echo '</div>';
        echo '</div>';
        // Títulos
        echo '<div class="main-container__content__title">';
        echo '<h1 class="animate-character">Ferron Hotels</h1>';
        echo '</div>';
        echo '<div class="main-container__content__subtitle">';
        echo '<h2 class="text txt-white">OUR HOTELS</h2>';
        echo '</div>';
        echo '</div>'; // Cierre del contenedor principal
        echo '<a href="index.php?controller=Hotel&action=inicioHoteles" class="btn btn-primary">Back</a>';
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

    public function mostrarHoteles($hoteles)
    {
        echo '<div class="container mt-4 ml-12">';
        echo '<div class="rowleft row">';
        if (isset($hoteles) && is_array($hoteles)) {
            foreach ($hoteles as $hotel) { //$hoteles es un array con objetos hotel dentro(stdClass)
                echo '<div class="col-md-6 mb-6">';
                echo '<div class="card cardm">';

                if ($hotel->foto) {
                    echo '<img src="' . $hotel->foto . '" class="card-img-top card-img" alt="Foto del hotel">';
                } else {
                    echo '<img src="../assets/images/header-1.jpeg" class="card-img  " alt="Foto no especifa de hotel">';
                }

                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $hotel->nombre . '</h5>';
                echo '<p class="card-text">' . $hotel->descripcion . '</p>';

                echo '<ul class="list-group list-group-flush">';
                echo '<li class="list-group-item"><strong>Dirección:</strong> ' . $hotel->direccion . '</li>';
                echo '<li class="list-group-item"><strong>Ciudad:</strong> ' . $hotel->ciudad . '</li>';
                echo '<li class="list-group-item"><strong>País:</strong> ' . $hotel->pais . '</li>';
                echo '<li class="list-group-item"><strong>Número de habitaciones:</strong> ' . $hotel->num_habitaciones . '</li>';
                echo '</ul>';
                if ($_SESSION['usuario']->getRol() == 1) {
                    // Si el rol es 1 (Administrador), mostrar los botones
                    echo '<div class="buttons-container mt-3 d-flex justify-content-around ">';
                    //creo un form para enviar el id del hotel a modificar
                    echo '<form method="post" action="index.php?controller=Gest&action=obtenerHotelesPorId">';
                    echo '<input type="hidden" name="id" value="' . $hotel->id . '">';
                    echo '<button class="btn btn-primary">Modificar</button>';
                    echo '</form>';
                    //creo un form para enviar el id del hotel a gestionar
                    echo '<form method="post" action="index.php?controller=Gest&action=gestionHotel">';
                    echo '<input type="hidden" name="id_hotel" value="' . $hotel->id . '">';
                    echo '<button type="submit" class="btn btn-success">Gestionar</button>';
                    echo '</form>';
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
