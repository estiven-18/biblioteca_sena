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

$mysql->desconectar();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2>dashboardl</h2>
        <p>id y usuario <?php echo $_SESSION['id_usuario']; ?> (tipo: <?php echo $_SESSION['tipo_usuario']; ?>)</p>
        <p>total libros <?php echo $estadisticasLibros['total_libros']; ?></p>
        <p>total reservas: <?php echo $estadisticasReservas['total_reservas']; ?></p>

        //* lo que va a ver el admnistrador */
        <?php if ($admin): ?>
            <a href="gestionar_libros.php" class="btn btn-primary">gestionar libros</a>
        <?php endif; ?>
    </div>
</body>

</html>