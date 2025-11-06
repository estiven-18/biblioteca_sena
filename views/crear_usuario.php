<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'administrador' || $_SESSION['activo'] != "activo") {
  header("Location: login.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario - Biblioteca Sena</title>
                    <a href="gestionar_reservas.php" class="nav-link"><i class="bi bi-calendar-check me-2"></i>Reservas</a>
                    <a href="gestionar_prestamos.php" class="nav-link"><i class="bi bi-box-seam me-2"></i>Préstamos</a>
                    <a href="gestionar_usuarios.php" class="nav-link active"><i class="bi bi-people me-2"></i>Usuarios</a>
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
            <div class="content-header mb-4">
                <div>
                    <h2 class="fw-bold text-success mb-0">Crear Nuevo Usuario</h2>
                    <p class="text-muted">Complete los datos para registrar un nuevo usuario.</p>
                </div>
                <div class="user-info">
                    <i class="bi bi-person-circle me-2"></i><?php echo ucfirst($_SESSION['tipo_usuario']); ?>
                </div>
            </div>



            <form id="formCrearUsuario">
                <h4 class="mb-4"><i class="bi bi-person-circle me-2"></i> Datos del Usuario</h4>

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" placeholder="Nombre" required>
                </div>

                <div class="mb-3">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" class="form-control" id="apellido" placeholder="Apellido" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" placeholder="ejemplo@correo.com" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" placeholder="Contraseña" required>
                </div>

                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo de Usuario</label>
                    <select class="form-select" id="tipo" required>
                        <option value="cliente">Cliente</option>
                        <option value="administrador">Administrador</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success w-100 mt-3">
                    <i class="bi bi-person-plus me-2"></i>Crear Usuario
                </button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/js/scripts.js"></script>
</body>

</html>