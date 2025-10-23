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

$consulta = "SELECT * FROM usuario";
$resultado = $mysql->efectuarConsulta($consulta);
$mysql->desconectar();


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestionar Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2>Gestionar Usuarios</h2>
        <a href="crear_usuario.php" class="btn btn-success">Crear Nuevo Usuario</a>
        <table id="tablaUsuarios" class="table table-striped table-hover mt-3" style="width:100%;">
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
                        <td><?php echo $usuario['nombre']; ?></td>
                        <td><?php echo $usuario['apellido']; ?></td>
                        <td><?php echo $usuario['email']; ?></td>
                        <td><?php echo $usuario['tipo']; ?></td>
                        <td>
                            <!-- //!edita pero no la contraseña(auque ponque que se cambia lo contrseña es mentira, se queda la misma), solo los otros campos -->
                            <a href="editar_usuario.php?id=<?php echo $usuario['id']; ?>" class="btn btn-primary">Editar</a>
                            <!-- //! no elimina porque es fk en otras tablas y da error al eliminar -->
                            <button class="btn btn-danger btnEliminarUsuario" data-id="<?php echo $usuario['id']; ?>">Eliminar</button>
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