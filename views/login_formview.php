

<?php
class Login_formview
{



    public function mostrarFormulario()
    {  // Genera el formulario
        echo '<form class="form" action="index.php?controller=User&action=procesarFormulario" method="POST">';
        echo '<label>Título:</label>';
        echo '<input type="text" name="titulo"">';
        echo '<br>';
        echo '<label>Completada:</label>';
        echo '<input type="checkbox" name="completada">';
        echo '<br>';
        echo '<input type="submit" value="Guardar">';
        echo '</form>';
    }

    // Muestra un mensaje de error
    public function mostrarError($mensaje)
    {
        echo '<p class="error">Error: ' . $mensaje . '</p>';
    }

    // public function mostrarInicio()
    // {
    //     echo '</ul>';
    //     echo '<a href="index.php?controller=User&action=mostrarFormulario">Entrar</a>'; //Añade un enlace para entrar y mostrar el formulario de login
    // }
}
