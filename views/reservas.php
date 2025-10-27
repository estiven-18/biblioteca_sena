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

$consulta = "SELECT * FROM libro WHERE disponibilidad = 'Disponible'";
$resultado = $mysql->efectuarConsulta($consulta);
$mysql->desconectar();

$admin = $_SESSION['tipo_usuario'] === 'administrador';
?>
<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reservar Libros | Biblioteca Sena</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

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
      background-color: #ffffff;
      border-right: 1px solid #dee2e6;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 25px 15px;
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
      font-weight: 500;
    }

    .sidebar .nav-link.active,
    .sidebar .nav-link:hover {
      background-color: #d1e7dd;
      color: #198754;
    }

    .logout-btn {
      background-color: #dc3545;
      color: #fff;
      font-weight: 500;
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

    .content-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .user-info {
      background: #fff;
      padding: 10px 20px;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
      color: #198754;
    }

    .table {
      background-color: #fff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .btn-success {
      border-radius: 8px;
      font-weight: 500;
    }
  </style>
</head>

<body>

  <div class="dashboard-container">

    <div class="sidebar">
      <div>
        <h5 class="mb-4 d-flex align-items-center"><i class="bi bi-book-half me-2"></i>Biblioteca Sena</h5>
        <nav class="nav flex-column">
          <a href="gestionar_libros.php" class="nav-link"><i class="bi bi-journal-bookmark me-2"></i>Libros</a>
          <a href="gestionar_reservas.php" class="nav-link"><i class="bi bi-calendar-check me-2"></i>Reservas</a>
          <a href="gestionar_prestamos.php" class="nav-link"><i class="bi bi-box-seam me-2"></i>Préstamos</a>
          <a href="gestionar_usuarios.php" class="nav-link"><i class="bi bi-people me-2"></i>Usuarios</a>
          <a href="informes.php" class="nav-link"><i class="bi bi-bar-chart-line me-2"></i>Informes</a>
        </nav>
      </div>
      <a href="logout.php" class="btn logout-btn w-100 mt-4"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</a>
    </div>

    <div class="content">
      <div class="content-header mb-4">
        <div>
          <h2 class="fw-bold text-success mb-0">Reservar Libros</h2>
          <p class="text-muted">Selecciona un libro disponible para realizar una reserva</p>
        </div>
        <div class="user-info">
          <i class="bi bi-person-circle me-2"></i><?php echo ucfirst($_SESSION['tipo_usuario']); ?>
        </div>
      </div>

      <div class="table-responsive">
        <table id="tablaReservas" class="table table-hover table-bordered align-middle">
          <thead class="table-success">
            <tr>
              <th>Título</th>
              <th>Autor</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($libro = mysqli_fetch_assoc($resultado)): ?>
              <tr>
                <td><?php echo htmlspecialchars($libro['titulo']); ?></td>
                <td><?php echo htmlspecialchars($libro['autor']); ?></td>
                <td class="text-center">
                  <button class="btn btn-success btnReservar" data-id="<?php echo $libro['id']; ?>">
                    <i class="bi bi-calendar-plus me-1"></i> Reservar
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



  <script src="../assets/js/scripts.js"></script>
</body>

</html>