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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background-color: #fff;
            border-right: 1px solid #dee2e6;
            padding: 25px 15px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar h5 {
            color: #198754;
            font-weight: bold;
        }

        .sidebar .nav-link {
            color: #444;
            border-radius: 10px;
            padding: 10px 15px;
            margin-bottom: 8px;
            transition: 0.3s;
        }

        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background-color: #d1e7dd;
            color: #198754;
        }

        .logout-btn {
            background-color: #dc3545;
            color: #fff;
            border-radius: 8px;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background-color: #bb2d3b;
        }

        .content {
            flex: 1;
            padding: 40px;
            background-color: #f5f7fb;
        }

        table.dataTable thead {
            background-color: #198754;
            color: white;
        }

        .btn-sm {
            border-radius: 8px;
            font-size: 0.85rem;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">


        <div class="sidebar">
            <div>
                <h5 class="mb-4 d-flex align-items-center"><i class="bi bi-book-half me-2"></i>Biblioteca Sena</h5>
                <nav class="nav flex-column">
                    <a href="dashboard.php" class="nav-link"><i class="bi bi-house me-2"></i>Inicio</a>
                    <a href="gestionar_libros.php" class="nav-link"><i class="bi bi-journal-bookmark me-2"></i>Libros</a>
                    <a href="gestionar_reservas.php" class="nav-link active"><i class="bi bi-calendar-check me-2"></i>Reservas</a>
                    <a href="gestionar_prestamos.php" class="nav-link"><i class="bi bi-box-seam me-2"></i>Préstamos</a>
                    <a href="gestionar_usuarios.php" class="nav-link"><i class="bi bi-people me-2"></i>Usuarios</a>
                </nav>
            </div>
            <a href="logout.php" class="btn logout-btn w-100 mt-4">
                <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
            </a>
        </div>


        <div class="content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-success mb-0">Gestionar Reservas</h2>
                    <p class="text-muted">Aprobar, rechazar o convertir en préstamo las reservas activas.</p>
                </div>
                <div class="user-info bg-white p-2 rounded shadow-sm text-success">
                    <i class="bi bi-person-circle me-2"></i><?php echo ucfirst($_SESSION['tipo_usuario']); ?>
                </div>
            </div>

            <div class="card p-4 shadow-sm">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="text-success fw-bold"><i class="bi bi-calendar-check me-2"></i>Reservas Registradas</h5>
                    <a href="crear_reserva.php" class="btn btn-success btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>Nueva Reserva
                    </a>
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
                                <td><span class="badge bg-<?php echo $reserva['estado'] == 'pendiente' ? 'warning' : ($reserva['estado'] == 'aprobada' ? 'success' : 'danger'); ?>">
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