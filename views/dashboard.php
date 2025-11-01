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

//* se obtine la reservas para el cliente en especifico
//* es null por que la variable va a cambiar dependiendo del cliente
$resultadoMisReservas = null;

if ($admin) {
  //* estas son las estadisticas que le van a salir al admin
  $consultaLibros = "SELECT COUNT(*) AS total_libros FROM libro";
  $consultaUsuarios = "SELECT COUNT(*) AS total_usuarios FROM usuario";

  $consultaReservas = "SELECT COUNT(*) AS total_reservas FROM reserva";

  $resultadoUsuarios = $mysql->efectuarConsulta($consultaUsuarios);
$estadisticasUsuarios = mysqli_fetch_assoc($resultadoUsuarios);
} else {
  //* estas son las estadisticas que le van a salir al cliente, dependiendo de quien sea

  //*el distinc es para que no se cunates los repetidos es decir que para que se sume 1 a la lista tinee que ser un libro nuevo
  $consultaLibros = "SELECT COUNT(DISTINCT id_libro) AS total_libros FROM reserva WHERE id_usuario = $id_usuario";

  //* este cuenta todo los libros 
  $consultaReservas = "SELECT COUNT(*) AS total_reservas FROM reserva WHERE id_usuario = $id_usuario";


   $consultaMisReservas = "
        SELECT libro.titulo, reserva.fecha_reserva, reserva.estado
        FROM reserva
        JOIN libro ON reserva.id_libro = libro.id
        WHERE reserva.id_usuario = $id_usuario
        ORDER BY reserva.fecha_reserva DESC
    ";
  $resultadoMisReservas = $mysql->efectuarConsulta($consultaMisReservas);
}



$resultadoLibros = $mysql->efectuarConsulta($consultaLibros);
$estadisticasLibros = mysqli_fetch_assoc($resultadoLibros);

$resultadoReservas = $mysql->efectuarConsulta($consultaReservas);
$estadisticasReservas = mysqli_fetch_assoc($resultadoReservas);




$mysql->desconectar();
?>
<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Biblioteca Sena</title>
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
          <a href="dashboard.php" class="nav-link active"><i class="bi bi-house me-2"></i>Dashboard</a>
          <?php if ($admin): ?>
            <a href="gestionar_libros.php" class="nav-link"><i class="bi bi-journal-bookmark me-2"></i>Libros</a>
            <a href="gestionar_reservas.php" class="nav-link"><i class="bi bi-calendar-check me-2"></i>Reservas</a>
            <a href="gestionar_prestamos.php" class="nav-link"><i class="bi bi-box-seam me-2"></i>Préstamos</a>
            <a href="gestionar_usuarios.php" class="nav-link"><i class="bi bi-people me-2"></i>Usuarios</a>
            <a href="informes.php" class="nav-link"><i class="bi bi-bar-chart-line me-2"></i>Informes</a>
            <a href="historial_prestamos.php" class="nav-link "><i class="bi bi-clock-history me-2"></i>Historial Prestamos</a>
            <a href="historial_reservas.php" class="nav-link "><i class="bi bi-calendar-range me-2"></i>Historial Reservas</a>
          <?php else: ?>
            <a href="historial_prestamos.php" class="nav-link "><i class="bi bi-clock-history me-2"></i>Historial Prestamos</a>
            <a href="historial_reservas.php" class="nav-link "><i class="bi bi-calendar-range me-2"></i>Historial Reservas</a>
            <a href="perfil.php" class="nav-link"><i class="bi  bi-person-circle me-2"></i>Perfil</a>
          <?php endif; ?>
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
          <h2 class="fw-bold text-success mb-0">Dashboard</h2>
          <p class="text-muted">Bienvenido al sistema de gestión de Biblioteca Sena</p>
        </div>
        <div class="user-info">
          <i class="bi bi-person-circle me-2"></i><?php echo ($_SESSION['tipo_usuario']); ?>
        </div>
      </div>



      <div class="row g-4 mb-5 d-flex justify-content-center align-items-center">
        <div class="col-md-6 col-lg-3">
          <div class="card-dashboard">
            <div class="icon text-success"><i class="bi bi-book"></i></div>
            <h5><?php echo $admin ? 'Total Libros' : 'Libros Reservados'; ?></h5>
            <h3 class="fw-bold text-success"><?php echo $estadisticasLibros['total_libros']; ?></h3>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card-dashboard">
            <div class="icon text-primary"><i class="bi bi-calendar-check"></i></div>
            <h5><?php echo $admin  ? 'Total Reservas' : 'Mis Reservas'; ?></h5>
            <h3 class="fw-bold text-primary"><?php echo $estadisticasReservas['total_reservas']; ?></h3>
          </div>
        </div>



        <?php if ($admin): ?>
          <div class="col-md-6 col-lg-3">
            <div class="card-dashboard">
              <div class="icon text-warning"><i class="bi bi-people"></i></div>
              <h5>Usuarios Activos</h5>
              <h3 class="fw-bold text-warning"><?php echo $estadisticasUsuarios['total_usuarios']; ?></h3>
            </div>
          </div>
          
        <?php endif; ?>
      </div>



      <?php if (!$admin): ?>
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h4 class="fw-bold text-success"><i class="bi bi-calendar-check me-2"></i> Mis Reservas</h4>
          <a href="reservas.php" class="btn btn-success"><i class="bi bi-plus-lg"></i> Nueva Reserva</a>
        </div>

        <div class="table-responsive shadow-sm">
          <table id="tablaMisReservas" class="table table-hover bg-white table-bordered w-100 tablaMisReservas ">
            <thead class="table-success">
              <tr>
                <th>Libro</th>
                <th>Fecha</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>

              <?php while ($reserva = mysqli_fetch_assoc($resultadoMisReservas)): ?>
                <tr>
                  <td><?php echo $reserva['titulo']; ?></td>
                  <td><?php echo $reserva['fecha_reserva']; ?></td>
                  <td><?php echo ucfirst($reserva['estado']); ?></td>
                </tr>
              <?php endwhile; ?>


            <?php endif; ?>
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

  <!-- <script>
    //* boton logout
$(document).ready(function () {
  $("#btnLogout").on("click", function (e) {
    e.preventDefault();

    Swal.fire({
      title: "¿Cerrar sesión?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Cerrar sesión",
      cancelButtonText: "Cancelar",
      reverseButtons: true,
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "../controllers/logout_controller.php",

          type: "POST",
          success: function () {
            Swal.fire({
              icon: "success",
              title: "Sesión cerrada",
              timer: 2000,
              showConfirmButton: false,
            }).then(() => {
              window.location.href = "../views/login.php";
            });
          },
          error: function () {
            Swal.fire("Error", "No se pudo cerrar la sesión", "error");
          },
        });
      }
    });
  });
});
  </script> -->
</body>

</html>