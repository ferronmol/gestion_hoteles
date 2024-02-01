<?php
class reserView extends baseView
{

    /*
    * Método para mostrar la página de inicio de reservas
    * 
    */
    public function mostrarInicio($reservas)
    {
        echo '<div class="main-container__reservas">';
        echo '<div class="main-container__content__title">';
        echo '<h1 class="animate-character animate-character--mod">Bookings</h1>';
        echo '</div>';
        echo '<a href="index.php?controller=Hotel&action=inicioHoteles" class="btn btn-primary btn-custom">Back</a>';
        //echo $this->mostrarMensajes();
        echo $this->mostrarReservas($reservas);
        echo '</div>';
    }
    /*
    * Método para mostrar la tabla con las reservas
    * @param array $reservas Array con los objetos Reserva a mostrar
    */

    public function mostrarReservas($reservas) //recibe un array de objetos reservas
    {
        //var_dump($reservas);
        ob_start();
        echo '<div class="main-container__content__table">';
        echo '<table class="table table-striped table-dark">';
        echo $this->mostrarMensajes();
        echo '<thead>';
        echo '<tr>';
        echo '<th scope="col">ID</th>';
        echo '<th scope="col">Check-in</th>';
        echo '<th scope="col">Check-out</th>';
        echo '<th scope="col">User ID</th>';
        echo '<th scope="col">Hotel ID</th>';
        echo '<th scope="col">Room ID</th>';
        echo '<th scope="col">Create</th>';
        echo '<th scope="col">Edit</th>';
        echo '<th scope="col">Delete</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($reservas as $reserva) {
            echo '<tr>';
            echo '<th scope="row">' . $reserva->getId() . '</th>';
            echo '<td>' . $reserva->getFecha_entrada() . '</td>';
            echo '<td>' . $reserva->getFecha_salida() . '</td>';
            echo '<td>' . $reserva->getId_usuario() . '</td>';
            echo '<td>' . $reserva->getId_hotel() . '</td>';
            echo '<td>' . $reserva->getId_habitacion() . '</td>';
            echo '<td><a href="index.php?controller=Reser&action=hacerReserva" class="btn btn-success">Create</a></td>';
            echo '<td><a href="index.php?controller=Reser&action=modificarReserva&id=' . $reserva->getId() . '" class="btn btn-info">Edit</a></td>';
            echo '<td><a href="index.php?controller=Reser&action=eliminarReserva&id=' . $reserva->getId() . '" class="btn btn-danger">Delete</a></td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
        echo '</div>';
        $htmlContent = ob_get_clean();
        return $htmlContent;
    }


    /*
    * Método para mostrar el formulario de reservas
    * @param $reservaId Objeto Reserva a mostrar
    */

    public function mostrarFormularioMod($reservaId)
    {
        //var_dump($reservaId); //ok
        // Genera el formulario y le pongo un name a cada input para poder recuperar los datos modificados
        echo '<h5 class="animate-character">Change Booking ' . $reservaId->getId() . '</h5>';
        echo '<div class="form-container form-cmod">';
        echo '<form action="index.php?controller=Reser&action=procesarReservas" method="post">';
        echo '  <input type="hidden" name="id" value="' . $reservaId->getId() . '">'; //para enviar el id de la reserva en el formulario
        echo '  <div class="form-group">';
        echo '    <label for="id">ID</label>';
        echo '    <input type="text" readonly name="id" class="form-control" id="id" placeholder="ID de la reserva" value="' . $reservaId->getId() . '">';
        echo '  </div>';

        echo '  <div class="form-group">';
        echo '    <label for="id_usuario">ID del usuario: </label>';
        echo '    <input type="text" name= "id_usuario" class="form-control" id="id_usuario" placeholder="ID del usuario" value="' . $reservaId->getId_usuario() . '">';
        echo '  </div>';

        echo '  <div class="form-group">';
        echo '    <label for="id_hotel">ID del Hotel: </label>';
        echo '    <input type="text" name="id_hotel" class="form-control" id="id_hotel" placeholder="ID del Hotel" value="' . $reservaId->getId_hotel() . '">';
        echo '  </div>';

        echo '  <div class="form-group">';
        echo '    <label for="id_habitacion">ID de la Habitación: </label>';
        echo '    <input type="text" name="id_habitacion" class="form-control" id="id_habitacion" placeholder="ID de la Habitacion" value="' . $reservaId->getId_habitacion() . '">';
        echo '  </div>';

        echo '<div class="form-group">';
        echo '    <label for="fecha_entrada">Fecha de Entrada:</label>';
        echo '    <input type="date" name="fecha_entrada" class="form-control" id="fecha_entrada" value="' . $reservaId->getFecha_entrada() . '">';
        echo '</div>';

        echo '<div class="form-group">';
        echo '    <label for="fecha_salida">Fecha de Salida:</label>';
        echo '    <input type="date" name="fecha_salida" class="form-control" id="fecha_salida" value="' . $reservaId->getFecha_salida() . '">';
        echo '</div>';
        echo '  <button type="submit" name ="submit"class="btn btn-primary">Submit</button>';
        ///////
        echo '</form>';
        echo '<a href="index.php?controller=Reser&action=mostrarInicio" class="btn btn-primary">Back</a>';
    }
    public function mostrarFormularioCreate()
    {
        echo '<h5 class="animate-character">Create Booking </h5>';
        echo '<div class="form-container form-cmod">';
        echo '<form action="index.php?controller=Reser&action=procesarCreacionReservas" method="post">';
        echo '  <div class="form-group">';
        echo '    <label for="id_usuario">ID del usuario: </label>';
        echo '    <input type="text" required name= "id_usuario" class="form-control" id="id_usuario" placeholder="ID del usuario" value="">';
        echo '  </div>';

        echo '  <div class="form-group">';
        echo '    <label for="id_hotel">ID del Hotel: </label>';
        echo '    <input type="text" required name="id_hotel" class="form-control" id="id_hotel" placeholder="ID del Hotel" value="">';
        echo '  </div>';

        echo '  <div class="form-group">';
        echo '    <label for="id_habitacion">ID de la Habitación: </label>';
        echo '    <input type="text" required  name="id_habitacion" class="form-control" id="id_habitacion" placeholder="ID de la Habitacion" value="">';
        echo '  </div>';

        echo '<div class="form-group">';
        echo '    <label for="fecha_entrada">Fecha de Entrada:</label>';
        echo '    <input type="date" required name="fecha_entrada" class="form-control" id="fecha_entrada" value="">';
        echo '</div>';

        echo '<div class="form-group">';
        echo '    <label for="fecha_salida">Fecha de Salida:</label>';
        echo '    <input type="date"required name="fecha_salida" class="form-control" id="fecha_salida" value="">';
        echo '</div>';
        echo '  <button type="submit" name ="submit"class="btn btn-primary">Submit</button>';
        ///////
        echo '</form>';
        echo '<a href="index.php?controller=Reser&action=mostrarInicio" class="btn btn-primary">Back</a>';
    }
    /*
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
