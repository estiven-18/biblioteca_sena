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

$id_usuario = $_SESSION['id_usuario'];
$admin = $_SESSION['tipo_usuario'] === 'administrador';

//* Historial de reservas (para admin muestra todas)
$consultaAdmin = "
    SELECT reserva.id, libro.titulo, reserva.fecha_reserva, reserva.estado 
    FROM reserva 
    JOIN libro ON reserva.id_libro = libro.id
";
$resultadoAdmin = $mysql->efectuarConsulta($consultaAdmin);

//* Solo para el usuario actual
$consultaCliente = "
    SELECT reserva.id, libro.titulo, reserva.fecha_reserva, reserva.estado 
    FROM reserva 
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
    <title><?= $admin ? 'Historial de Reservas | Biblioteca Sena' : 'Mis Reservas | Biblioteca Sena'; ?></title>

    <!-- Bootstrap & DataTables -->
        table.dataTable {
            width: 100% !important;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <div>
                <h5 class="mb-4 d-flex align-items-center"><i class="bi bi-book-half me-2"></i>Biblioteca Sena</h5>
                <nav class="nav flex-column">
                    <a href="dashboard.php" class="nav-link"><i class="bi bi-house me-2"></i>Dashboard</a>
                    <?php if ($admin): ?>
                        <a href="gestionar_libros.php" class="nav-link"><i class="bi bi-journal-bookmark me-2"></i>Libros</a>
                        <a href="gestionar_reservas.php" class="nav-link"><i class="bi bi-calendar-check me-2"></i>Reservas</a>
                        <a href="gestionar_prestamos.php" class="nav-link"><i class="bi bi-box-seam me-2"></i>Préstamos</a>
                        <a href="gestionar_usuarios.php" class="nav-link"><i class="bi bi-people me-2"></i>Usuarios</a>
                        <a href="informes.php" class="nav-link"><i class="bi bi-bar-chart-line me-2"></i>Informes</a>
                        <a href="historial_prestamos.php" class="nav-link"><i class="bi bi-clock-history me-2"></i>Historial Prestamos</a>
                        <a href="historial_reservas.php" class="nav-link active"><i class="bi bi-calendar-range me-2"></i>Historial Reservas</a>
                    <?php else: ?>
                        <a href="historial_prestamos.php" class="nav-link "><i class="bi bi-clock-history me-2"></i>Historial Prestamos</a>
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
                        <?= $admin ? 'Historial de Reservas' : 'Mis Reservas'; ?>
                    </h2>
                    <p class="text-muted"><?= $admin ? 'Listado completo de todas las reservas registradas.' : 'Consulta tus reservas y su estado.'; ?></p>
                </div>
                <div class="user-info">
                    <i class="bi bi-person-circle me-2"></i><?php echo ($_SESSION['tipo_usuario']); ?>
                </div>
            </div>

            <div class="table-container">
                <table id="tablaHistorialReservas" class="table table-striped table-hover">
                    <thead class="table-success">
                        <tr>
                            <th>ID</th>
                            <th>Libro</th>
                            <th>Fecha de Reserva</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $datos = $admin ? $resultadoAdmin : $resultadoCliente;
                        while ($reserva = mysqli_fetch_assoc($datos)): ?>
                            <tr>
                                <td><?= $reserva['id']; ?></td>
                                <td><?= htmlspecialchars($reserva['titulo']); ?></td>
                                <td><?= $reserva['fecha_reserva']; ?></td>
                                <td><?= $reserva['estado']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="../assets/js/scripts.js"></script>
    <script>
        $(document).ready(function() {
            $('#tablaHistorialReservas').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
                },
                pageLength: 10,
                order: [
                    [0, 'asc']
                ]
            });
        });
    </script>
</body>

</html>
