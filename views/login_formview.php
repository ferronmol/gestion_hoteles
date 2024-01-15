
<?php

class Login_formview
{

    public function mostrarFormulario()
    {
        // Genera el formulario
        echo '<div class="main-container__content">';
        echo '<div class="main-container__content__title">';
        echo '<h3 class="animate-character">Account</h3>';
        echo '</div>';
        echo '<div class="form-container">';
        echo '<form action="index.php?controller=User&action=procesarFormulario" method="post">';
        echo '<div class="form-container__input">';
        echo '<label for="nombre">Nombre</label>';
        echo '<input type="text" name="nombre" id="nombre" placeholder="Nombre de Usuario" required>';
        echo '</div>';
        echo '<div class="form-container__input">';
        echo '<label for="password">Password</label>';
        echo '<input type="password" name="password" id="password" placeholder="Password" required>';
        echo '</div>';
        echo '<div class="form-container__input">';
        echo '<button type="submit" class="btn-entrar" id="btn-entrar"><span>Login</span></button>';
        echo '</div>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
    }


    // Muestra un mensaje de error
    public function mostrarError($mensaje)
    {
        echo '<p class="error">Error: ' . $mensaje . '</p>';
    }
}
