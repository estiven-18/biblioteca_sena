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

$id = $_GET['id'];
$consulta = "SELECT * FROM libro WHERE id = $id";
$resultado = $mysql->efectuarConsulta($consulta);
$libro = mysqli_fetch_assoc($resultado);
$mysql->desconectar();


?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Libro - Biblioteca Sena</title>
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
          <a href="dashboard.php" class="nav-link "><i class="bi bi-house me-2"></i>Dashboard</a>

          <a href="gestionar_libros.php" class="nav-link active"><i class="bi bi-journal-bookmark me-2"></i>Libros</a>
          <a href="gestionar_reservas.php" class="nav-link"><i class="bi bi-calendar-check me-2"></i>Reservas</a>
          <a href="gestionar_prestamos.php" class="nav-link"><i class="bi bi-box-seam me-2"></i>Préstamos</a>
          <a href="gestionar_usuarios.php" class="nav-link"><i class="bi bi-people me-2"></i>Usuarios</a>
          <a href="informes.php" class="nav-link"><i class="bi bi-bar-chart-line me-2"></i>Informes</a>
          <a href="historial_prestamos.php" class="nav-link "><i class="bi bi-clock-history me-2"></i>Historial Prestamos</a>
          <a href="historial_reservas.php" class="nav-link "><i class="bi bi-calendar-range me-2"></i>Historial Reservas</a>

        </nav>
      </div>

      <button class="btn logout-btn w-100 mt-4 btnLogout">
        <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
        </a></button>
    </div>


    <div class="content">
      <div class="content-header mb-4">
        <div>
          <h2 class="fw-bold text-success mb-0">Editar Libro</h2>
          <p class="text-muted">Modifica los datos del libro seleccionado.</p>
        </div>
        <div class="user-info">
          <i class="bi bi-person-circle me-2"></i><?php echo ($_SESSION['tipo_usuario']); ?>
        </div>
      </div>


      <form id="formEditarLibro">
        <input type="hidden" id="id" value="<?php echo $libro['id']; ?>">

        <h4 class="mb-4"><i class="bi bi-journal-bookmark me-2"></i> Datos del Libro</h4>

        <div class="mb-3">
          <label for="titulo" class="form-label">Título</label>
          <input type="text" class="form-control" id="titulo" value="<?php echo htmlspecialchars($libro['titulo']); ?>" required>
        </div>

        <div class="mb-3">
          <label for="autor" class="form-label">Autor</label>
          <input type="text" class="form-control" id="autor" value="<?php echo htmlspecialchars($libro['autor']); ?>" required>
        </div>

        <!-- <div class="mb-3">
                    <label for="ISBN" class="form-label">ISBN</label>
                    <input type="number" class="form-control" id="ISBN" value="<?php echo htmlspecialchars($libro['ISBN']); ?>" required>
                </div> -->

        <div class="mb-3">
          <label for="categoria" class="form-label">Categoría</label>
          <input type="text" class="form-control" id="categoria" value="<?php echo htmlspecialchars($libro['categoria']); ?>" required>
        </div>

        <div class="mb-3">
          <label for="cantidad" class="form-label">Cantidad</label>
          <input type="number" class="form-control" id="cantidad" value="<?php echo htmlspecialchars($libro['cantidad']); ?>" min="0" required>
        </div>

        <div class="mb-3">
          <label for="disponibilidad" class="form-label">Disponibilidad</label>
          <select class="form-select" id="disponibilidad" required>
            <option value="Disponible" <?php if ($libro['disponibilidad'] == 'Disponible') echo 'selected'; ?>>Disponible</option>
            <option value="No disponible" <?php if ($libro['disponibilidad'] == 'No disponible') echo 'selected'; ?>>No disponible</option>
          </select>
        </div>



        <div class="d-flex justify-content-between mt-4">
          <a href="gestionar_libros.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Cancelar</a>
          <button type="submit" class="btn btn-success"><i class="bi bi-save me-2"></i>Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../assets/js/scripts.js"></script> 
</body>


</html>