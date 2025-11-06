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

//* consulta reservas con nombre de usuario y libro
$consulta = "
    SELECT reserva.id, usuario.nombre, libro.titulo, reserva.fecha_reserva, reserva.estado 
    FROM reserva 
    JOIN usuario ON reserva.id_usuario = usuario.id 
    JOIN libro ON reserva.id_libro = libro.id
";
$resultado = $mysql->efectuarConsulta($consulta);
$mysql->desconectar();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestionar Reservas - Biblioteca Sena</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <a href="gestionar_libros.php" class="nav-link"><i class="bi bi-journal-bookmark me-2"></i>Libros</a>
                    <a href="gestionar_reservas.php" class="nav-link active"><i class="bi bi-calendar-check me-2"></i>Reservas</a>
                    <a href="gestionar_prestamos.php" class="nav-link"><i class="bi bi-box-seam me-2"></i>Préstamos</a>
                    <a href="gestionar_usuarios.php" class="nav-link"><i class="bi bi-people me-2"></i>Usuarios</a>
                    <a href="informes.php" class="nav-link"><i class="bi bi-bar-chart-line me-2"></i>Informes</a>
                    <a href="historial_prestamos.php" class="nav-link "><i class="bi bi-clock-history me-2"></i>Historial Prestamos</a>
                    <a href="historial_reservas.php" class="nav-link "><i class="bi bi-calendar-range me-2"></i>Historial Reservas</a>

                </nav>
            </div>

            <!--//! organizar y poner en todas las views -->
            <button class="btn logout-btn w-100 mt-4 btnLogout">
                <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                </a></button>
        </div>


        <div class="content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-success mb-0">Gestionar Reservas</h2>
                    <p class="text-muted">Aprobar, rechazar o convertir en préstamo las reservas activas.</p>
                </div>
                <div class="user-info bg-white p-2 rounded shadow-sm text-success">
                    <i class="bi bi-person-circle me-2"></i><?php echo ($_SESSION['tipo_usuario']); ?>
                </div>
            </div>

            <div class="card p-4 shadow-sm">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="text-success fw-bold"><i class="bi bi-calendar-check me-2"></i>Reservas Registradas</h5>
                    <!-- <a href="crear_reserva.php" class="btn btn-success btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>Nueva Reserva
                    </a> -->
                </div>

                <table id="tablaReservas" class="table table-striped table-hover">
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
                                <td><?php echo htmlspecialchars($reserva['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($reserva['titulo']); ?></td>
                                <td><?php echo $reserva['fecha_reserva']; ?></td>
                                <td><span class="badge bg-<?php echo $reserva['estado'] == 'pendiente' ? 'warning' : ($reserva['estado'] == 'aprobada' ? 'success' : ($reserva['estado'] == 'creado' ? 'success' : 'danger'));?>">
                                        <?php echo ucfirst($reserva['estado']); ?></span>
                                </td>
                                <td>
                                    <?php if ($reserva['estado'] == 'pendiente'): ?>
                                        <button class="btn btn-success btn-sm btnAprobar" data-id="<?php echo $reserva['id']; ?>">
                                            <i class="bi bi-check2-circle"></i> Aprobar
                                        </button>
                                        <button class="btn btn-danger btn-sm btnRechazar" data-id="<?php echo $reserva['id']; ?>">
                                            <i class="bi bi-x-circle"></i> Rechazar
                                        </button>
                                    <?php elseif ($reserva['estado'] == 'aprobada'): ?>
                                        <button class="btn btn-info btn-sm btnCrearPrestamo" data-id="<?php echo $reserva['id']; ?>">
                                            <i class="bi bi-bookmark-plus"></i> Crear Préstamo
                                        </button>
                                    <?php elseif ($reserva['estado'] == 'creado'): ?>
                                        <span class="text-success fw-bold">Préstamo generado</span>
                                    <?php endif; ?>
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
