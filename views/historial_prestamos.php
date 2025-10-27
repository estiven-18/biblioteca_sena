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
$id_usuario = $_SESSION['id_usuario'];

// Consulta general (solo para admin)
$consulta = "
    SELECT prestamo.id, libro.titulo, prestamo.fecha_prestamo, 
           prestamo.fecha_devolucion, prestamo.estado 
    FROM prestamo 
    JOIN reserva ON prestamo.id_reserva = reserva.id 
    JOIN libro ON reserva.id_libro = libro.id
";
$resultado = $mysql->efectuarConsulta($consulta);

// Consulta solo para el cliente actual
$consultaCliente = "
    SELECT prestamo.id, libro.titulo, prestamo.fecha_prestamo, 
           prestamo.fecha_devolucion, prestamo.estado 
    FROM prestamo 
    JOIN reserva ON prestamo.id_reserva = reserva.id 
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
    <title>Historial de Préstamos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>

<body>
    <?php if ($admin): ?>
        <div class="container mt-4">
            <h2>Historial de Préstamos</h2>
            <table id="tablaHistorialPrestamos" class="table table-striped table-hover mt-3" style="width:100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Libro</th>
                        <th>Fecha Préstamo</th>
                        <th>Fecha Devolución</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($prestamo = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <td><?= $prestamo['id']; ?></td>
                            <td><?= htmlspecialchars($prestamo['titulo']); ?></td>
                            <td><?= $prestamo['fecha_prestamo']; ?></td>
                            <td><?= $prestamo['fecha_devolucion']; ?></td>
                            <td><?= $prestamo['estado']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <a href="dashboard.php" class="btn btn-secondary">Volver al Dashboard</a>
        </div>
    <?php else: ?>
        <div class="container mt-4">
            <h2>Mi Historial de Préstamos</h2>
            <table id="tablaHistorialPrestamos" class="table table-striped table-hover mt-3" style="width:100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Libro</th>
                        <th>Fecha Préstamo</th>
                        <th>Fecha Devolución</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($prestamo = mysqli_fetch_assoc($resultadoCliente)): ?>
                        <tr>
                            <td><?= $prestamo['id']; ?></td>
                            <td><?= htmlspecialchars($prestamo['titulo']); ?></td>
                            <td><?= $prestamo['fecha_prestamo']; ?></td>
                            <td><?= $prestamo['fecha_devolucion']; ?></td>
                            <td><?= $prestamo['estado']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <a href="dashboard.php" class="btn btn-secondary">Volver al Dashboard</a>
        </div>
    <?php endif; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="../assets/js/scripts.js"></script>

</body>

</html>
