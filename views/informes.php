<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'administrador') {
  header("Location: login.php");
  exit();
}
?>
<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Informes | Biblioteca Sena</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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

    .card-dashboard {
      border: none;
      border-radius: 16px;
      background-color: #fff;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
      text-align: center;
      padding: 20px;
      transition: 0.3s;
    }

    .card-dashboard:hover {
      transform: translateY(-3px);
    }

    .card-dashboard .icon {
      font-size: 32px;
      margin-bottom: 10px;
    }

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
          <a href="dashboard.php" class="nav-link "><i class="bi bi-house me-2"></i>DashBoard</a>

          <a href="gestionar_libros.php" class="nav-link"><i class="bi bi-journal-bookmark me-2"></i>Libros</a>
          <a href="gestionar_reservas.php" class="nav-link"><i class="bi bi-calendar-check me-2"></i>Reservas</a>
          <a href="gestionar_prestamos.php" class="nav-link"><i class="bi bi-box-seam me-2"></i>Préstamos</a>
          <a href="gestionar_usuarios.php" class="nav-link"><i class="bi bi-people me-2"></i>Usuarios</a>
          <a href="informes.php" class="nav-link active"><i class="bi bi-bar-chart-line me-2"></i>Informes</a>
          <a href="historial_prestamos.php" class="nav-link"><i class="bi bi-bar-chart-line me-2"></i>Historial Prestamos</a>
          <a href="historial_reservas.php" class="nav-link"><i class="bi bi-bar-chart-line me-2"></i>Historial Reservas</a>

        </nav>
      </div>

      <!--//! organizar y poner en todas las views -->
      <button class="btn logout-btn w-100 mt-4 btnLogout">
        <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
        </a></button>
    </div>

    <div class="content">
      <div class="content-header mb-4">
        <div>
          <h2 class="fw-bold text-success mb-0">Generación de Informes</h2>
          <p class="text-muted">Cree y exporte reportes sobre libros, usuarios, reservas y más.</p>
        </div>
        <div class="user-info">
          <i class="bi bi-person-circle me-2"></i><?php echo ($_SESSION['tipo_usuario']); ?>
        </div>
      </div>

      <div class="card-form">
        <form id="formInforme">
          <div class="mb-3">
            <label for="tipo_informe" class="form-label fw-semibold">Tipo de Informe</label>
            <select class="form-select" id="tipo_informe" required>
              <option value="inventario">Inventario de Libros</option>
              <option value="prestamos">Historial de Préstamos</option>
              <option value="reservas">Historial de Reservas</option>
              <option value="mas_prestados">Libros Más Prestados</option>
              <option value="usuarios">Usuarios Registrados</option>
            </select>
          </div>

          <div class="row mb-3">
            <div class="col">
              <label for="fecha_inicio" class="form-label fw-semibold">Desde</label>
              <input type="date" id="fecha_inicio" class="form-control">
            </div>
            <div class="col">
              <label for="fecha_fin" class="form-label fw-semibold">Hasta</label>
              <input type="date" id="fecha_fin" class="form-control">
            </div>
          </div>

          <div class="d-flex gap-3">
            <button type="button" id="btnPDF" class="btn btn-danger">
              <i class="bi bi-filetype-pdf me-2"></i>Generar PDF
            </button>

            <!--//! no sirveeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee -->
            <button type="button" id="btnExcel" class="btn btn-success">
              <i class="bi bi-filetype-xlsx me-2"></i>Exportar Excel
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../assets/js/scripts.js"></script>
</body>

</html>