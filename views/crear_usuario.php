<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'administrador') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Crear Nuevo Usuario</h2>
        <!-- //*funciona correctamente -->
        <form id="formCrearUsuario">
            <input type="text" id="nombre" placeholder="Nombre" required>
            <input type="text" id="apellido" placeholder="Apellido" required>
            <input type="email" id="email" placeholder="Email" required>
            <input type="password" id="password" placeholder="Contraseña" required>
            <select id="tipo" required>
                <option value="cliente">Cliente</option>
                <option value="administrador">Administrador</option>
            </select>
            <button type="submit">Crear Usuario</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/scripts.js"></script>
</body>
</html>