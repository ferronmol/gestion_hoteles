<?php
class hotelView extends baseView

{
    /** 
     * Método para mostrar la interfaz de inicio
     * @param string $mensajeError Mensaje de error a mostrar
     */
    public function inicioHoteles($mensajeError = null)
    {
        echo '<div class="main-container__content">';
        echo '<div class="button-container">';
        echo '<div class="booking-container">';
        echo '<a href="index.php?controller=Reser&action=listarReservas" class="btn-reservas pos"><span>RESERVAS</span></a>';
        echo '</div>';
        echo '<div class="checkout-container">';
        echo '<a href="index.php?controller=User&action=cerrarSesion" class="btn-salir"><span>Out Session</span></a>';
        echo '</div>';
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
        echo '<h3 class="text txt-white">OUR HOTELS</h3>';
        echo '</div>';
        echo '</div>'; // Cierre del contenedor principal
        $this->mostrarMensajes();
        echo '<a href="index.php?controller=Hotel&action=inicioHoteles" class="btn btn-primary btn-custom">Back</a>';
    }

    /**
     * Método para mostrar los hoteles en la interfaz
     * @param array $hoteles Array con los objetos Hotel a mostrar
     */

    public function mostrarHoteles($hoteles)
    {

        echo '<div class="container mt-4 ml-12">';
        $this->mostrarMensajes();
        echo '<div class="rowleft row">';
        if (isset($hoteles) && is_array($hoteles)) {
            foreach ($hoteles as $hotel) { //$hoteles es un array con objetos hotel dentro(stdClass)
                echo '<div class="col-md-6 mb-6">';
                echo '<div class="card cardm">';

                if ($hotel->foto) {
                    echo '<img src="data:image/jpeg;base64,' . base64_encode($hotel->foto) . '" width = "50" height = "200" class="card-img-top card-img" alt="Foto del hotel">';
                } else {
                    $imgPred = ['barcelonahotel1.avif', 'madridhotel1.avif', 'madridhotel2.avif'];
                    $imgAlt = $imgPred[array_rand($imgPred)]; // $imgPred es un array con nombres de imágenes
                    echo '<img src="../assets/images/' . $imgAlt . '" class="card-img" alt="Foto no especifa de hotel">';
                }

                echo '<div class="card-body">';
                echo '<h5 class="card-title whitexl">' . $hotel->nombre . '</h5>';
                echo '<p class="card-text white">' . $hotel->descripcion . '</p>';

                echo '<ul class="list-group list-group-flush bg-custom">';
                echo '<li class="list-group-item bg-custom"><strong>Dirección:</strong> ' . $hotel->direccion . '</li>';
                echo '<li class="list-group-item bg-custom"><strong>Ciudad:</strong> ' . $hotel->ciudad . '</li>';
                echo '<li class="list-group-item bg-custom"><strong>País:</strong> ' . $hotel->pais . '</li>';
                echo '<li class="list-group-item bg-custom"><strong>Número de habitaciones:</strong> ' . $hotel->num_habitaciones . '</li>';
                echo '</ul>';
                if ($_SESSION['usuario']->getRol() == 1) {
                    // Si el rol es 1 (Administrador), mostrar los botones
                    echo '<div class="buttons-container mt-3 d-flex justify-content-around ">';
                    //creo un form para enviar el id del hotel a modificar
                    echo '<form method="post" action="index.php?controller=Gest&action=obtenerHotelesPorId">';
                    echo '<input type="hidden" name="id" value="' . $hotel->id . '">';
                    echo '<input type="hidden" name="ciudad" value="' . $hotel->ciudad . '">';
                    echo '<input type="hidden" name="nombre" value="' . $hotel->nombre . '">';
                    echo '<button class="btn btn-primary">Modificar</button>';
                    echo '</form>';
                    //creo un form para enviar el id del hotel y la ciudad  a gestionar
                    echo '<form method="post" action="index.php?controller=Gest&action=mostrarHabitaciones">';
                    echo '<input type="hidden" name="id_hotel" value="' . $hotel->id . '">';
                    echo '<button type="submit" class="btn btn-success">Ver Habitaciones</button>';
                    echo '</form>';
                    // Botón para crear habitaciones
                    echo '<form method="post" action="index.php?controller=Gest&action=mostrarFormularioCrearHabitaciones">';
                    echo '<input type="hidden" name="id_hotel" value="' . $hotel->id . '">';
                    echo '<button type="submit" class="btn btn-warning">Crear Habitaciones</button>';
                    echo '</form>';
                    // Botón para borrar hotel
                    echo '<form method="post" action="index.php?controller=Gest&action=borrarHotel">';
                    echo '<input type="hidden" name="id_hotel" value="' . $hotel->id . '">';
                    echo '<input type="hidden" name="ciudad" value="' . $hotel->ciudad . '">';
                    echo '<input type="hidden" name="nombre" value="' . $hotel->nombre . '">';
                    echo '<button type="submit" class="btn btn-danger">Borrar</button>';
                    echo '</form>';
                    echo '</div>';
                }
                if ($_SESSION['usuario']->getRol() == 0) {
                    // Si el rol es 0 (Usuario), mostrar el botón
                    echo '<div class="buttons-container mt-3 d-flex justify-content-around ">';
                    //creo un form para enviar el id del hotel y la ciudad  a gestionar
                    echo '<form method="post" action="index.php?controller=Gest&action=mostrarHabitaciones">';
                    echo '<input type="hidden" name="id_hotel" value="' . $hotel->id . '">';
                    echo '<input type="hidden" name="ciudad" value="' . $hotel->ciudad . '">';
                    echo '<input type="hidden" name="nombre" value="' . $hotel->nombre . '">';
                    echo '<button type="submit" class="btn btn-success">Ver Habitaciones</button>';
                    echo '</form>';
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
    /** 
     * Método para mostrar mensajes en la interfaz
     * 
     */
    public function mostrarMensajes()
    {
        // Verifica si hay mensajes de éxito
        if ($this->mensajeExito) {
            echo '<div class="alert alert-success space auto-dismiss " id="mensajeExito">' . $this->mensajeExito . '</div>';
        }

        // Verifica si hay mensajes de error
        if ($this->mensajeError) {
            echo '<div class="alert alert-danger space auto-dismiss-error" id="mensajeError">' . $this->mensajeError . '</div>';
        }
    }
}
