<?php
class hotelView
{
    public function inicioHoteles()
    {
        echo '<div class="main-container__content">';
        echo '<div class=checkout-container>';
        echo '<a href="index.php?controller=User&action=cerrarSesion" class="btn-salir"><span>Out Session</span></a>';
        echo '</div>';
        echo '<p class="text text--min">Usuario: ' . $_SESSION['usuario']->getNombre() . '</p>';
        if ($_SESSION['usuario']->getRol() == 1) {
            echo '<p class="text text--min">Administrador</p>';
        } else {
            echo '<p class="text text--min">Usuario</p>';
        }
        echo '<div class="main-container__content__title">';
        echo '<h1 class="animate-character">Ferron Hotels</h1>';
        echo '</div>';
        echo '<div class="main-container__content__subtitle">';
        echo '<h2 class="text txt-white">OUR HOTELS</h2>';
        echo '</div>';
    }
}
