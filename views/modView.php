<?php
class modView extends baseView
{
    public function mostrarInicio()
    {
        echo '<div class="main-container__content">';
        echo '<div class="main-container__content__title">';
        echo '<h1 class="animate-character">Ferron Hotels</h1>';
        echo '</div>';
        echo '<div class="main-container__content__subtitle">';
        echo '<h2 class="text txt-white">Where luxury meets comfort</h2>';
        echo '</div>';
        echo '<div class="main-container__content__btn">';
        echo '<form action="index.php?controller=User&action=mostrarFormulario" method="get">';
        echo ' <input type="hidden" name="action" value="mostrarFormulario">';
        echo '<button type="submit" class="btn-entrar" id="btn-entrar"><span>Entrar</span></button>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
    }

    /*
    * Método para mostrar el formulario de creación de hoteles
    * @return void
    */

    public function mostrarFormularioMod($hotel, $mensajeError = null)
    {
        // Genera el formulario y le pongo un name a cada input para poder recuperar los datos modificados
        echo '<h5 class="animate-character">Change Hotel ' . $hotel->getId() . '</h5>';
        echo '<div class="form-container form-cmod">';
        echo '<form  class="form" action="index.php?controller=Gest&action=recibirFormularioMod" method="post" enctype="multipart/form-data">';
        ///////
        echo '  <input type="hidden" name="id" value="' . $hotel->getId() . '">'; //para enviar el id del hotel en el formulario
        echo '  <div class="form-group">';
        echo '    <label for="nombre">Nombre del Hotel:</label>';
        echo '    <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombre del hotel" value="' . $hotel->getNombre() . '">';
        echo '  </div>';

        echo '  <div class="form-group">';
        echo '    <label for="direccion">Dirección:</label>';
        echo '    <input type="text" name= "direccion" class="form-control" id="direccion" placeholder="Dirección del hotel" value="' . $hotel->getDireccion() . '">';
        echo '  </div>';

        echo '  <div class="form-group">';
        echo '    <label for="ciudad">Ciudad:</label>';
        echo '    <input type="text" readonly  name="ciudad" class="form-control" id="ciudad" placeholder="Ciudad del hotel" value="' . $hotel->getCiudad() . '">';
        echo '  </div>';

        echo '  <div class="form-group">';
        echo '    <label for="pais">País:</label>';
        echo '    <input type="text" readonly name="pais" class="form-control" id="pais" placeholder="País del hotel" value="' . $hotel->getPais() . '">';
        echo '  </div>';

        echo '  <div class="form-group">';
        echo '    <label for="num_habitaciones">Número de Habitaciones</label>';
        echo '    <input type="number" name="num_habitaciones" class="form-control" id="num_habitaciones" placeholder="Número de habitaciones del hotel" value="' . $hotel->getNum_habitaciones() . '">';
        echo '  </div>';

        echo '  <div class="form-group">';
        echo '    <label for="descripcion">Descripción</label>';
        echo '    <textarea class="form-control" name ="descripcion" id="descripcion" placeholder="Descripción del hotel">' . $hotel->getDescripcion() . '</textarea>';
        echo '  </div>';

        echo '  <div class="form-group">';
        echo '    <label for="foto">Seleccionar una imagen:</label>';
        echo '    <input type="file" name="foto" class="form-control" id="foto" placeholder="Subir foto del hotel" accept="image/jpeg, image/png, image/gif">';
        echo '  </div>';

        echo '  <button type="submit" class="btn btn-primary">Submit</button>';
        ///////
        echo '</form>';
        echo '<a href="index.php?controller=Hotel&action=inicioHoteles" class="btn btn-primary btn-custom">Back</a>';
        // Muestra los mensajes en la interfaz de usuario
        $this->mostrarMensajes();
    }
    /*
        * Método para mostrar el formulario de creación de hoteles
        * @return void
        */

    public function crearHabitaciones($hotel, $mensajeError = null)
    {
        // Genera el formulario y le pongo un name a cada input para poder recuperar los datos modificados
        echo '<h5 class="animate-character">Create in ' . $hotel->getNombre() . '</h5>';
        echo '<div class="form-container form-cmod2">';
        echo '<form class="form" action="index.php?controller=Gest&action=recibirFormularioCrearHabitaciones" method="post">';
        ///////
        echo '  <input type="hidden" name="id_hotel" value="' . $hotel->getId() . '">'; //para enviar el id del hotel en el formulario
        echo '  <div class="form-group">';
        echo '    <label for="num_habitacion">Número de Habitacion</label>';
        echo '    <input type="number" name="num_habitacion" class="form-control" id="numero" placeholder="Número de habitacion" value="">';
        echo '  </div>';

        echo '  <div class="form-group">';
        echo '    <label for="tipo">Tipo</label>';
        echo '<select name="tipo" class="form-select" id="tipo">';
        echo '        <option value="individual">Individual</option>';
        echo '        <option value="doble">Doble</option>';
        echo '        <option value="suite">Suite</option>';
        echo '    </select>';
        echo '  </div>';

        echo '  <div class="form-group">';
        echo '    <label for="precio">Precio</label>';
        echo '    <input type="number" name="precio" class="form-control" id="precio" placeholder="Precio" value="">';
        echo '  </div>';

        echo '  <div class="form-group">';
        echo '    <label for="descripcion">Descripción</label>';
        echo '    <textarea class="form-control" name ="descripcion" id="descripcion" placeholder="Descripción de la Habitación"></textarea>';
        echo '  </div>';

        echo '  <button type="submit" class="btn btn-primary btn-custom">Submit</button>';
        ///////
        echo '</form>';
        echo '<a href="index.php?controller=Hotel&action=inicioHoteles" class="btn btn-primary btn-custom">Back</a>';
        $this->mostrarMensajes();
    }
    /*
        * Métod para mostrar el formulario de modificación de habitaciones
        * @param Habitacion $habitacion
        * @return void
        */

    public function mostrarFormularioModHabitaciones($habitacion, $mensajeError = null)
    {
        // Genera el formulario y le pongo un name a cada input para poder recuperar los datos modificados
        echo '<h5 class="animate-character">Change Room Nº ' . $habitacion->getNum_habitacion() . '</h5>';
        echo '<div class="form-container form-cmod2">';
        echo '<form class="form" action="index.php?controller=Gest&action=recibirFormularioModHabitaciones" method="post" enctype="multipart/form-data">';
        ///////
        echo '  <input type="hidden" name="id" value="' . $habitacion->getId() . '">'; //para enviar el id de la hab en el formulario
        echo '  <input type="hidden" name="id_hotel" value="' . $habitacion->getId_hotel() . '">'; //para enviar el id del hotel en el formulario   
        echo '  <div class="form-group">';
        echo '    <label for="num_habitacion">Número de Habitacion</label>';
        echo '    <input type="number" name="num_habitacion" class="form-control" id="num_habitacion" placeholder="Número de habitacion: " value="' . $habitacion->getNum_habitacion() . '">';
        echo '  </div>';

        echo '  <div class="form-group">';
        echo '    <label for="tipo">Tipo</label>';
        echo '<select name="tipo" class="form-select" id="tipo">';
        echo '        <option value="individual" ' . ($habitacion->getTipo() === 'individual' ? 'selected' : '') . '>Individual</option>';
        echo '        <option value="doble" ' . ($habitacion->getTipo() === 'doble' ? 'selected' : '') . '>Doble</option>';
        echo '        <option value="suite" ' . ($habitacion->getTipo() === 'suite' ? 'selected' : '') . '>Suite</option>';
        echo '    </select>';
        echo '  </div>';

        echo '  <div class="form-group">';
        echo '    <label for="precio">Precio</label>';
        echo '    <input type="number" name="precio" class="form-control" id="precio" placeholder="Precio" value="' . $habitacion->getPrecio() . '">';
        echo '  </div>';

        echo '  <div class="form-group">';
        echo '    <label for="descripcion">Descripción</label>';
        echo '    <textarea class="form-control" name ="descripcion" id="descripcion" placeholder="Descripción de la Habitación">' . $habitacion->getDescripcion() . '</textarea>';
        echo '  </div>';

        echo '  <button type="submit" class="btn btn-primary btn-custom">Submit</button>';
        ///////
        echo '</form>';
        echo '<a href="index.php?controller=Hotel&action=inicioHoteles" class="btn btn-primary btn-custom">Back</a>';
        $this->mostrarMensajes();
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
