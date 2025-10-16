<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
//* Para que solo los administradores accedan
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'administrador') {
    header("Location: login.php");
    exit();
}

require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

$consulta = "SELECT * FROM libro";
$resultado = $mysql->efectuarConsulta($consulta);
$mysql->desconectar(); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>gestion de libros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet"> 
</head>
<body>
    <div class="container">
        <h2>gestion de libros</h2>
        <a href="agregar_libros.php" class="btn btn-success">agragrt libro</a>
        <table id="tablaLibros" class="table table-striped table-hover mt-3" style="width:100%;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titulo</th>
                    <th>Autor</th>
                    <th>isbn</th>
                    <th>categoria</th>
                    <th>disponibilidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($libro = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td><?php echo $libro['id']; ?></td>
                        <td><?php echo $libro['titulo']; ?></td>
                        <td><?php echo $libro['autor']; ?></td>
                        <td><?php echo $libro['ISBN']; ?></td>
                        <td><?php echo $libro['categoria']; ?></td>
                        <td><?php echo $libro['disponibilidad']; ?></td>
                        

                        <td>
                            <a href="editar_libro.php?id=<?php echo $libro['id']; ?>" class="btn btn-primary">Editar</a>
                            <a href="eliminar_libro.php?id=<?php echo $libro['id']; ?>" class="btn btn-danger">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tablaLibros').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'  
                },
                responsive: true  //* Hace la tabla responsive
            });
        });
    </script>
</body>
</html>
