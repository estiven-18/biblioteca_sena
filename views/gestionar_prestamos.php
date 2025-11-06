<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'administrador' || $_SESSION['activo'] != "activo") {
    header("Location: login.php");
    exit();
}

require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

//* consulta para obtener prestamos activos con usuario y libro
$consulta = "
    SELECT prestamo.id, usuario.nombre, libro.titulo, prestamo.fecha_prestamo, prestamo.fecha_devolucion 
    FROM prestamo 
    JOIN reserva ON prestamo.id_reserva = reserva.id 
    JOIN usuario ON reserva.id_usuario = usuario.id 
    JOIN libro ON reserva.id_libro = libro.id 
    WHERE prestamo.estado = 'activo'
";

$resultado = $mysql->efectuarConsulta($consulta);
$mysql->desconectar();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Préstamos - Biblioteca Sena</title>
        /* Resaltar fila vencida */
        .fila-vencida {
            background-color: #ffe6e6 !important;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">

        <div class="sidebar">
            <div>
                <h5 class="mb-4 d-flex align-items-center"><i class="bi bi-book-half me-2"></i>Biblioteca Sena</h5>
                <nav class="nav flex-column">
                    <a href="dashboard.php" class="nav-link "><i class="bi bi-house me-2"></i>Dashboard</a>

                    <a href="gestionar_libros.php" class="nav-link"><i class="bi bi-journal-bookmark me-2"></i>Libros</a>
                    <a href="gestionar_reservas.php" class="nav-link"><i class="bi bi-calendar-check me-2"></i>Reservas</a>
                    <a href="gestionar_prestamos.php" class="nav-link active"><i class="bi bi-box-seam me-2"></i>Préstamos</a>
                    <a href="gestionar_usuarios.php" class="nav-link"><i class="bi bi-people me-2"></i>Usuarios</a>
                    <a href="informes.php" class="nav-link"><i class="bi bi-bar-chart-line me-2"></i>Informes</a>
                    <a href="historial_prestamos.php" class="nav-link "><i class="bi bi-clock-history me-2"></i>Historial Prestamos</a>
                    <a href="historial_reservas.php" class="nav-link "><i class="bi bi-calendar-range me-2"></i>Historial Reservas</a>

                </nav>
            </div>

            <button class="btn logout-btn w-100 mt-4 btnLogout">
                <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
            </button>
        </div>

        <div class="content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-success mb-0">Gestionar Préstamos</h2>
                    <p class="text-muted">Control de préstamos activos y devoluciones.</p>
                </div>
                <div class="user-info">
                    <i class="bi bi-person-circle me-2"></i><?php echo ($_SESSION['tipo_usuario']); ?>
                </div>
            </div>

            <div class="card p-4 shadow-sm">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="text-success fw-bold mb-0"><i class="bi bi-box-seam me-2"></i>Préstamos Activos</h5>
                </div>

                <table id="tablaPrestamos" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Libro</th>
                            <th>Fecha Préstamo</th>
                            <th>Fecha Devolución</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                         //*se obtine la fecha de hoy */ 
                        $hoy = date('Y-m-d');
                        while ($prestamo = mysqli_fetch_assoc($resultado)):
                            //* se mira si el pretamo esta vencido
                            $estaVencido = $prestamo['fecha_devolucion'] < $hoy;
                            
                        ?>
                            <tr >
                                <td><?php echo $prestamo['id']; ?></td>
                                <td><?php echo htmlspecialchars($prestamo['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($prestamo['titulo']); ?></td>
                                <td><?php echo $prestamo['fecha_prestamo']; ?></td>
                                <td>
                                    <!-- //* si esta vencio se pone al lado un texto que dice VENCIDO -->
                                    <?php echo $prestamo['fecha_devolucion']; ?>
                                    <?php if ($estaVencido): ?>
                                        <span class="badge bg-danger ms-2">VENCIDO</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-success btn-sm btnDevolver" data-id="<?php echo $prestamo['id']; ?>">
                                        <i class="bi bi-check-circle me-1"></i>Devolver
                                    </button>
                                </td>
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

</body>

</html>