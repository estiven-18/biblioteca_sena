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

$id_usuario = $_SESSION['id_usuario'];
$admin = $_SESSION['tipo_usuario'] === 'administrador';

//* historial de reservas para admin es decir todas las reservas 
$consultaAdmin = "
    SELECT reserva.id, libro.titulo, reserva.fecha_reserva, reserva.estado 
    FROM reserva 
    JOIN libro ON reserva.id_libro = libro.id
";
$resultadoAdmin = $mysql->efectuarConsulta($consultaAdmin);

//* consulta solo para el cliente actual 
$consultaCliente = "
    SELECT reserva.id, libro.titulo, reserva.fecha_reserva, reserva.estado 
    FROM reserva 
    JOIN libro ON reserva.id_libro = libro.id
    WHERE reserva.id_usuario = $id_usuario
";
$resultadoCliente = $mysql->efectuarConsulta($consultaCliente);

$mysql->desconectar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Reservas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>
    <?php if ($admin): ?>
        <div class="container mt-4">
            <h2>Historial de Reservas</h2>
            <table id="tablaHistorialReservas" class="table table-striped table-hover mt-3" style="width:100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Libro</th>
                        <th>Fecha de Reserva</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($reserva = mysqli_fetch_assoc($resultadoAdmin)): ?>
                        <tr>
                            <td><?= $reserva['id']; ?></td>
                            <td><?= htmlspecialchars($reserva['titulo']); ?></td>
                            <td><?= $reserva['fecha_reserva']; ?></td>
                            <td><?= $reserva['estado']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <a href="dashboard.php" class="btn btn-secondary mt-3">Volver al Dashboard</a>
        </div>
    <?php else: ?>
        <div class="container mt-4">
            <h2>Mis Reservas</h2>
            <table id="tablaHistorialReservas" class="table table-striped table-hover mt-3" style="width:100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Libro</th>
                        <th>Fecha de Reserva</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($reserva = mysqli_fetch_assoc($resultadoCliente)): ?>
                        <tr>
                            <td><?= $reserva['id']; ?></td>
                            <td><?= htmlspecialchars($reserva['titulo']); ?></td>
                            <td><?= $reserva['fecha_reserva']; ?></td>
                            <td><?= $reserva['estado']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <a href="dashboard.php" class="btn btn-secondary mt-3">Volver al Dashboard</a>
        </div>
    <?php endif; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="../assets/js/scripts.js"></script>
    
</body>
</html>