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

$admin = $_SESSION['tipo_usuario'] === 'administrador';
$consultaLibros = "SELECT COUNT(*) as total_libros FROM libro";
$resultadoLibros = $mysql->efectuarConsulta($consultaLibros);
$estadisticasLibros = mysqli_fetch_assoc($resultadoLibros);

$consultaReservas = "SELECT COUNT(*) as total_reservas FROM reserva";
$resultadoReservas = $mysql->efectuarConsulta($consultaReservas);
$estadisticasReservas = mysqli_fetch_assoc($resultadoReservas);

//!esto es para los clientes que se  muestre sus reservas
if (!$admin) {
    $id_usuario = $_SESSION['id_usuario'];
    $consultaMisReservas = "SELECT libro.titulo, reserva.fecha_reserva, reserva.estado FROM reserva JOIN libro ON reserva.id_libro = libro.id WHERE reserva.id_usuario = $id_usuario";
    $resultadoMisReservas = $mysql->efectuarConsulta($consultaMisReservas);
}

$mysql->desconectar();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2>Dashboard</h2>
        <p>ID y usuario: <?php echo $_SESSION['id_usuario']; ?> (tipo: <?php echo $_SESSION['tipo_usuario']; ?>)</p>
        <p>Total libros: <?php echo $estadisticasLibros['total_libros']; ?></p>
        <p>Total reservas: <?php echo $estadisticasReservas['total_reservas']; ?></p>

        <!-- Lo que ve el administrador -->
        <?php if ($admin): ?>
            <a href="gestionar_libros.php" class="btn btn-primary">Gestionar Libros</a>
            <a href="gestionar_reservas.php" class="btn btn-warning">Gestionar Reservas</a>
        <?php endif; ?>

        <!-- Lo que ve el cliente -->

        <?php if (!$admin): ?>
            <a href="reservas.php" class="btn btn-success">Reservar Libros</a>
            <h3>Mis Reservas</h3>
            <table id="tablaMisReservas" class="table table-striped table-hover mt-3" style="width:100%;">
                <thead>
                    <tr>
                        <th>Libro</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($reserva = mysqli_fetch_assoc($resultadoMisReservas)): ?>
                        <tr>
                            <td><?php echo $reserva['titulo']; ?></td>
                            <td><?php echo $reserva['fecha_reserva']; ?></td>
                            <td><?php echo $reserva['estado']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="../assets/js/scripts.js"></script>
</body>

</html>