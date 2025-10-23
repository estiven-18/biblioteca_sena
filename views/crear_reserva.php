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

$consultaUsuarios = "SELECT id, nombre, apellido FROM usuario";
$resultadoUsuarios = $mysql->efectuarConsulta($consultaUsuarios);

$consultaLibros = "SELECT id, titulo FROM libro WHERE disponibilidad = 'Disponible'";
$resultadoLibros = $mysql->efectuarConsulta($consultaLibros);
$mysql->desconectar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Reserva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Crear Nueva Reserva</h2>
        <form id="formCrearReserva">
            <!-- //? esto es para que el administrador pueda seleccionar el usuario y el libro a reservar -->
            <select id="id_usuario" required>
                <option value="">Seleccionar Usuario</option>
                <?php while ($usuario = mysqli_fetch_assoc($resultadoUsuarios)): ?>
                    <option value="<?php echo $usuario['id']; ?>"><?php echo $usuario['nombre'] . ' ' . $usuario['apellido']; ?></option>
                <?php endwhile; ?>
            </select>
            <select id="id_libro" required>
                <option value="">Seleccionar Libro</option>
                <?php while ($libro = mysqli_fetch_assoc($resultadoLibros)): ?>
                    <option value="<?php echo $libro['id']; ?>"><?php echo $libro['titulo']; ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Crear Reserva</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/scripts.js"></script>
</body>
</html>
