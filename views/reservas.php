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
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reservar Libros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2>Reservar Libros</h2>
        <table id="tablaReservas" class="table table-striped table-hover mt-3" style="width:100%;">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($libro = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td><?php echo $libro['titulo']; ?></td>
                        <td><?php echo $libro['autor']; ?></td>
                        <td>
                            <button class="btn btn-primary btnReservar" data-id="<?php echo $libro['id']; ?>">Reservar</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="../assets/js/scripts.js"></script>
</body>

</html>