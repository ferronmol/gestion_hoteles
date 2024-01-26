<?php
class userView
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

    public function mostrarFormulario()
    {
        // Genera el formulario
        echo '<div class="main-container__content">';
        echo '<div class="main-container__content__title">';
        echo '<h3 class="animate-character">ACCOUNT</h3>';
        echo '</div>';
        echo '<div class="form-container">';
        echo '<form action="index.php?controller=User&action=procesarFormulario" method="post">';
        echo '<div class="input-group">';
        echo '<label for="nombre">Username</label>';
        echo '<input type="text" name="nombre" id="nombre" placeholder="Enter username" required>';
        echo '</div>';
        echo '<div class="input-group">';
        echo '<label for="contraseña">Password</label>';
        echo '<input type="password" name="contraseña" id="contraseña" placeholder="Password min 6 character" required>';
        echo '</div>';
        echo '<div class="form-container__input">';
        echo '<button type="submit" class="btn-entrar" id="btn-entrar"><span>Login</span></button>';
        echo '</div>';
        echo '</form>';
        echo '<a href="../index.php" class="btn btn-primary">Back</a>';
        echo '</div>';
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
}
