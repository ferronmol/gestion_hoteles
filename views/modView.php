<?php
class modView
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

    public function mostrarFormularioMod($hotel)
    {
        // Genera el formulario y le pongo un name a cada input para poder recuperar los datos modificados
        echo '<h5 class="animate-character">Change Hotel ' . $hotel->getId() . '</h5>';
        echo '<div class="form-container form-cmod">';
        echo '<form action="index.php?controller=Gest&action=recibirFormularioMod" method="post" enctype="multipart/form-data">';
        ///////
        echo '  <input type="hidden" name="id" value="' . $hotel->getId() . '">'; //para enviar el id del hotel en el formulario
        echo '  <div class="form-group">';
        echo '    <label for="nombre">Nombre</label>';
        echo '    <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombre del hotel" value="' . $hotel->getNombre() . '">';
        echo '  </div>';

        echo '  <div class="form-group">';
        echo '    <label for="direccion">Dirección</label>';
        echo '    <input type="text" name= "direccion" class="form-control" id="direccion" placeholder="Dirección del hotel" value="' . $hotel->getDireccion() . '">';
        echo '  </div>';

        echo '  <div class="form-group">';
        echo '    <label for="ciudad">Ciudad</label>';
        echo '    <input type="text" name="ciudad" class="form-control" id="ciudad" placeholder="Ciudad del hotel" value="' . $hotel->getCiudad() . '">';
        echo '  </div>';

        echo '  <div class="form-group">';
        echo '    <label for="pais">País</label>';
        echo '    <input type="text" name="pais" class="form-control" id="pais" placeholder="País del hotel" value="' . $hotel->getPais() . '">';
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
        echo '    <label for="foto">Foto</label>';
        echo '    <input type="file" name="foto" class="form-control" id="foto" placeholder="Subir foto del hotel">';
        echo '  </div>';

        echo '  <button type="submit" class="btn btn-primary">Submit</button>';
        ///////
        echo '</form>';
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
    public function crearHabitaciones($id_hotel)
    {
        // Genera el formulario y le pongo un name a cada input para poder recuperar los datos modificados
        echo '<h5 class="animate-character">Change Hotel ' . $id_hotel . '</h5>';
        echo '<div class="form-container form-cmod2">';
        echo '<form class="form" action="index.php?controller=Gest&action=recibirFormularioCrearhabitaciones" method="post" enctype="multipart/form-data">';
        ///////
        echo '  <input type="hidden" name="id_hotel" value="' . $id_hotel . '">'; //para enviar el id del hotel en el formulario
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

        echo '  <button type="submit" class="btn btn-primary">Submit</button>';
        ///////
        echo '</form>';
        echo '<a href="index.php?controller=Hotel&action=inicioHoteles" class="btn btn-primary">Back</a>';
    }
}
