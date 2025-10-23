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

//* consulta para obtener los prestamos activos junto con el nombre del usuario y el título del libro 
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
    <title>Gestionar Préstamos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h2 class="mb-3">Gestionar Préstamos</h2>

        <!--//* tabla para mostrar los prestamos con la opcion de marcar como devuelto -->
        <table id="tablaPrestamos" class="table table-striped table-hover">
            <thead class="table-dark">
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
                <?php while ($prestamo = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td><?php echo $prestamo['id']; ?></td>
                        <td><?php echo htmlspecialchars($prestamo['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($prestamo['titulo']); ?></td>
                        <td><?php echo $prestamo['fecha_prestamo']; ?></td>
                        <td><?php echo $prestamo['fecha_devolucion']; ?></td>
                        <td>
                            <!-- //* cuando se le da al boton de devolver, se marca el prestamo como 'devuelto' en la base de datos -->
                            <!-- //! se creo otro campo en la tabal de prestamo llamado 'estado' para saber si el prestamo esta activo o devuelto -->
                            <button class="btn btn-success btnDevolver btn-sm" data-id="<?php echo $prestamo['id']; ?>">
                                Marcar Devuelto
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/js/scripts.js"></script>


</body>

</html>