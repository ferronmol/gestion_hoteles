<!-- login_formview.php -->
<h2>Iniciar sesión</h2>
<form action="../frontcontroller.php" method="post">
    <input type="hidden" name="controller" value="User>
    <input type=" hidden" name="action" value="procesarLogin">
    <!-- Agrega aquí los campos del formulario de inicio de sesión -->
    <label for="username">Usuario:</label>
    <input type="text" id="username" name="username" required><br>

    <label for="password">Contraseña:</label>
    <input type="password" id="password" name="password" required><br>

    <button type="submit" name="entrar">Iniciar sesión</button>
</form>