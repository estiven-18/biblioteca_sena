<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['id_usuario']) || $_SESSION['activo'] != "activo") {
  session_destroy();
  header("Location: login.php?error=inactivo");
  exit();
}

require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

$admin = $_SESSION['tipo_usuario'] === 'administrador';
$id_usuario = $_SESSION['id_usuario'];

//* consulta general (solo para admin)
$consulta = "
    SELECT prestamo.id, libro.titulo, prestamo.fecha_prestamo, 
           prestamo.fecha_devolucion, prestamo.estado 
    FROM prestamo 
    JOIN reserva ON prestamo.id_reserva = reserva.id 
    JOIN libro ON reserva.id_libro = libro.id
";
$resultado = $mysql->efectuarConsulta($consulta);

//* consulta solo para el cliente actual
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
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Historial de Préstamos | Biblioteca Sena</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

                    <?php if ($admin): ?>
                        <a href="gestionar_libros.php" class="nav-link"><i class="bi bi-journal-bookmark me-2"></i>Libros</a>
                        <a href="gestionar_reservas.php" class="nav-link"><i class="bi bi-calendar-check me-2"></i>Reservas</a>
                        <a href="gestionar_prestamos.php" class="nav-link"><i class="bi bi-box-seam me-2"></i>Préstamos</a>
                        <a href="gestionar_usuarios.php" class="nav-link"><i class="bi bi-people me-2"></i>Usuarios</a>
                        <a href="informes.php" class="nav-link"><i class="bi bi-bar-chart-line me-2"></i>Informes</a>
                        <a href="historial_prestamos.php" class="nav-link active"><i class="bi bi-clock-history me-2"></i>Historial Prestamos</a>
                        <a href="historial_reservas.php" class="nav-link "><i class="bi bi-calendar-range me-2"></i>Historial Reservas</a>
                    <?php else: ?>
                        <a href="historial_prestamos.php" class="nav-link active"><i class="bi bi-clock-history me-2"></i>Historial Prestamos</a>
                        <!-- <a href="historial_reservas.php" class="nav-link "><i class="bi bi-calendar-range me-2"></i>Historial Reservas</a> -->
                        <a href="perfil.php" class="nav-link"><i class="bi bi-person-circle me-2"></i>Perfil</a>
                    <?php endif; ?>
                </nav>
            </div>

            <button class="btn logout-btn w-100 mt-4 btnLogout">
                <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
            </button>
        </div>

        <div class="content">
            <div class="content-header mb-4">
                <div>
                    <h2 class="fw-bold text-success mb-0">
                        <?= $admin ? 'Historial de Préstamos' : 'Mis Préstamos'; ?>
                    </h2>
                    <p class="text-muted"><?= $admin ? 'Listado completo de todos los préstamos realizados.' : 'Consulta tus préstamos y su estado.'; ?></p>
                </div>
                <div class="user-info">
                    <i class="bi bi-person-circle me-2"></i><?php echo ($_SESSION['tipo_usuario']); ?>
                </div>
            </div>

            <div class="table-container">
                <table id="tablaHistorialPrestamos" class="table table-striped table-hover">
                    <thead class="table-success">
                        <tr>
                            <th>ID</th>
                            <th>Libro</th>
                            <th>Fecha Préstamo</th>
                            <th>Fecha Devolución</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $datos = $admin ? $resultado : $resultadoCliente;
                        while ($prestamo = mysqli_fetch_assoc($datos)): ?>
                            <tr>
                                <td><?= $prestamo['id']; ?></td>
                                <td><?= htmlspecialchars($prestamo['titulo']); ?></td>
                                <td><?= $prestamo['fecha_prestamo']; ?></td>
                                <td><?= $prestamo['fecha_devolucion']; ?></td>
                                <td><?= ($prestamo['estado']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/js/scripts.js"></script>


    <script>
        $(document).ready(function() {
            $('#tablaHistorialPrestamos').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
                },
                responsive: true
            });
        });
    </script>

</body>

</html>
