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
//* consulta para obtener las reservas junto con el nombre del usuario y el título del libro
$consulta = "SELECT reserva.id, usuario.nombre, libro.titulo, reserva.fecha_reserva, reserva.estado FROM reserva JOIN usuario ON reserva.id_usuario = usuario.id JOIN libro ON reserva.id_libro = libro.id";
$resultado = $mysql->efectuarConsulta($consulta);
$mysql->desconectar();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestionar Reservas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2>Gestionar Reservas</h2>
        <a href="crear_reserva.php" class="btn btn-success">Crear Nueva Reserva</a>

        <!--//* tabla para mostrar las reservas con acciones para aprobar, rechazar o crear prestamo segun el estado dela basa de datos -->
        <table id="tablaReservas" class="table table-striped table-hover mt-3" style="width:100%;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Libro</th>
                    <th>Fecha Reserva</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($reserva = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td><?php echo $reserva['id']; ?></td>
                        <td><?php echo $reserva['nombre']; ?></td>
                        <td><?php echo $reserva['titulo']; ?></td>
                        <td><?php echo $reserva['fecha_reserva']; ?></td>
                        <td><?php echo $reserva['estado']; ?></td>
                        <td>
                            <!-- //* la base de datos pone por defecto el estado 'pendiente', y si es pendiente, le muestra la opciones de rechazar o aprobar -->
                            <?php if ($reserva['estado'] == 'pendiente'): ?>
                                <button class="btn btn-success btnAprobar" data-id="<?php echo $reserva['id']; ?>">Aprobar</button>
                                <button class="btn btn-danger btnRechazar" data-id="<?php echo $reserva['id']; ?>">Rechazar</button>
                                <!-- //* si el estado es aprobada, le muestra la opcion de crear prestamo */ -->
                                <!-- //? el pretamo se crea con la fecha de el dia en el que se hizo y la fecha de devolucion es 10 dias despues -->
                                <?php elseif ($reserva['estado'] == 'aprobada'): ?>
                                    <button class="btn btn-info btnCrearPrestamo" data-id="<?php echo $reserva['id']; ?>">Crear Préstamo</button>
                                <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="../assets/js/scripts.js"></script>
</body>

</html>