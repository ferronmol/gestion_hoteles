<?php
include_once('./db/DB.php');
include_once('./models/UserModel.php');
include_once('./models/HotelModel.php');
include_once('./controllers/UserController.php');
include_once('./controllers/HotelController.php');
include_once('./views/userView.php');
include_once('./views/hotelView.php');



// Define la acción por defecto
define('ACCION_DEFECTO', 'mostrarInicio'); //listar es la acción por defecto que es la funcion mostrarLogin de LoginController

// Define el controlador por defecto
define('CONTROLADOR_DEFECTO', 'User'); //user es el controlador por defecto que es la clase UserController

// Comprueba la acción a realizar, que llegará en la petición.
// Si no hay acción a realizar lanzará la acción por defecto, que es mostrar el login
// Y se carga la acción, llama a la función cargarAccion
function lanzarAccion($controllerObj)
{

    //method_exists() es una función predefinida de PHP que permite comprobar si un 
    //método existe en un objeto dado.
    if (isset($_GET["action"]) && method_exists($controllerObj, $_GET["action"])) {
        cargarAccion($controllerObj, $_GET["action"]);
    } else {
        cargarAccion($controllerObj, ACCION_DEFECTO);
        //O añadir una página indicando el error de la acción
        //die("Se ha cargado una acción errónea");
    }
}

// Lo que hace es ejecutar una función que va a existir en el controlador
// y que se llama como la acción. Recibe un objeto controlador.
function cargarAccion($controllerObj, $action)
{
    $accion = $action;
    $controllerObj->$accion();
}


// Carga el controlador especificado y devuelve una instancia del mismo
function cargarControlador($nombreControlador)
{
    $controlador = $nombreControlador . 'Controller';
    if (class_exists($controlador)) {
        return new $controlador();
    } else {
        // Si el controlador no existe, se lanza una excepción
        //O añadir una página indicando el error del controlador
        header("Location: ./views/errorView.php");
    }
}

// Carga el controlador y la acción correspondientes (si existe get y si no))
if (isset($_GET["controller"])) { //Si existe el controlador,el principio caraga el index y por tanto no existe
    $controllerObj = cargarControlador($_GET["controller"]);
    lanzarAccion($controllerObj);
} else { //Si no existe el controlador, carga el controlador por defecto y la acción por defecto
    $controllerObj = cargarControlador(CONTROLADOR_DEFECTO);
    lanzarAccion($controllerObj);
}
