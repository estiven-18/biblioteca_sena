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

$consulta = "SELECT * FROM usuario WHERE contrasena != 'ñ(ZJDl-SW3D,.'";
$resultado = $mysql->efectuarConsulta($consulta);

$mysql->desconectar();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestionar Usuarios - Biblioteca SENA</title>
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-success mb-0">Gestionar Usuarios</h2>
                    <p class="text-muted">Edita o elimina usuarios registrados del sistema.</p>
                </div>
                <div class="user-info bg-white p-2 rounded shadow-sm text-success">
                    <i class="bi bi-person-circle me-2"></i><?php echo ($_SESSION['tipo_usuario']); ?>
                </div>
            </div>

            <div class="card p-4 shadow-sm">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="text-success fw-bold"><i class="bi bi-people me-2"></i>Usuarios Registrados</h5>
                    <a href="crear_usuario.php" class="btn btn-success btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>Nuevo Usuario
                    </a>
                </div>

                <table id="tablaUsuarios" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                            <th>Tipo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($usuario = mysqli_fetch_assoc($resultado)): ?>
                            <tr>
                                <td><?php echo $usuario['id']; ?></td>
                                <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['apellido']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $usuario['tipo'] == 'administrador' ? 'success' : 'secondary'; ?>">
                                        <?php echo ucfirst($usuario['tipo']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="editar_usuario.php?id=<?php echo $usuario['id']; ?>" class="btn btn-primary btn-sm">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </a>
                                    <button class="btn btn-danger btn-sm btnEliminarUsuario" data-id="<?php echo $usuario['id']; ?>">
                                        <i class="bi bi-trash3"></i> Eliminar
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