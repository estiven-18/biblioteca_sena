<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'administrador') {
    header("Location: login.php");
    exit();
}
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();
//* obtetenemso el id del usuario a editar
$id = $_GET['id'];
$consulta = "SELECT * FROM usuario WHERE id = $id";
$resultado = $mysql->efectuarConsulta($consulta);
$usuario = mysqli_fetch_assoc($resultado);
$mysql->desconectar();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2>Editar Usuario</h2>
        <form id="formEditarUsuario">
            <!-- //* todo funciona corretamente-->
            <input type="hidden" id="id" value="<?php echo $usuario['id']; ?>">
            <input type="text" id="nombre" value="<?php echo $usuario['nombre']; ?>" required>
            <input type="text" id="apellido" value="<?php echo $usuario['apellido']; ?>" required>
            <input type="email" id="email" value="<?php echo $usuario['email']; ?>" required>
            <select id="tipo" required>
                <option value="cliente" <?php if ($usuario['tipo'] == 'cliente') echo 'selected'; ?>>Cliente</option>
                <option value="administrador" <?php if ($usuario['tipo'] == 'administrador') echo 'selected'; ?>>Administrador</option>
            </select>
            <!-- //! la contraseña no se cambia y se queda la anterior -->
            <input type="password" id="password" placeholder="Nueva Contraseña (opcional)">
            <button type="submit">Editar Usuario</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/scripts.js"></script>
</body>

</html>
