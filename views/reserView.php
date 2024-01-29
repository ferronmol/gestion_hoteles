<?php
class reserView
{

    public function mostrarInicio()
    {
        echo '<div class="main-container__reservas">';
        echo '<div class="main-container__content__title">';
        echo '<h1 class="animate-character animate-character--mod">Bookings</h1>';
        echo '</div>';
        echo '<a href="index.php?controller=Hotel&action=inicioHoteles" class="btn btn-primary">Back</a>';
        echo '</div>';
    }
}
