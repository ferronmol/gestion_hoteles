<?php
//fucnion mostrar formualrio de login
function showLogin()
{
    require_once '../views/login.php';
}


//fucnion que hace todas las verificaciones de seguridad en el formulario del login

function login($db)
{
    $username = $_POST['username'];
    $password = $_POST['password'];
    $errors = checkForm($username, $password);
    if (empty($errors)) {
        $user = $db->getUserByUsername($username);
        if ($user) {
            if (password_verify($password, $user->getPassword())) {
                $_SESSION['user'] = $user;
                header('Location: index.php');
            } else {
                $errors['password'] = 'La contraseña no es correcta';
            }
        } else {
            $errors['username'] = 'El usuario no existe';
        }
    }
    return $errors;
}

function checkForm($username, $password)
{
    $errors = [];
    if (empty($username)) {
        $errors['username'] = 'El nombre de usuario no puede estar vacío';
    }
    if (empty($password)) {
        $errors['password'] = 'La contraseña no puede estar vacía';
    }
    return $errors;
}
