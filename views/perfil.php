<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();
$id = $_SESSION['id_usuario'];
$consulta = "SELECT * FROM usuario WHERE id = $id";
$resultado = $mysql->efectuarConsulta($consulta);
$usuario = mysqli_fetch_assoc($resultado);
$mysql->desconectar();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mi Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2>Mi Perfil</h2>
        <form id="formEditarPerfil">
            <input type="hidden" id="id" value="<?php echo $usuario['id']; ?>">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" id="nombre" class="form-control" value="<?php echo $usuario['nombre']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" id="apellido" class="form-control" value="<?php echo $usuario['apellido']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" class="form-control" value="<?php echo $usuario['email']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Nueva Contraseña (opcional)</label>
                <input type="password" id="password" class="form-control" placeholder="Deja vacío si no quieres cambiar">
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Perfil</button>

        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/scripts.js"></script>
</body>

</html>