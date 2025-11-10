<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion'])) {
    //* la accion la mando desde el front para saber que hacer 
    //* crear
    if ($_POST['accion'] == 'crear') {
        $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_SPECIAL_CHARS);
        $apellido = filter_var($_POST['apellido'], FILTER_SANITIZE_SPECIAL_CHARS);
        $email = $_POST['email'];
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $tipo = $_POST['tipo'];


        $verificarEmail = "SELECT id FROM usuario WHERE email = '$email'";
        $resultadoVerificacion = $mysql->efectuarConsulta($verificarEmail);

        if ($resultadoVerificacion && mysqli_num_rows($resultadoVerificacion) > 0) {
            echo json_encode(["status" => "error", "message" => "El correo electrónico ya está registrado."]);
            exit;
        }

        $consulta = "INSERT INTO usuario (nombre, apellido, email, contrasena, tipo, activo) VALUES ('$nombre', '$apellido', '$email', '$password', '$tipo',activo='activo')";
        $resultado = $mysql->efectuarConsulta($consulta);
        if ($resultado) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al crear usuario"]);
        }
        //* editar el usuario pero el admin lo edita
    } elseif ($_POST['accion'] == 'editar') {
        $id = $_POST['id'];
        $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_SPECIAL_CHARS);
        $apellido = filter_var($_POST['apellido'], FILTER_SANITIZE_SPECIAL_CHARS);
        $email = $_POST['email'];
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        $tipo = $_POST['tipo'];

        $consultaEmail = "SELECT id FROM usuario WHERE email = '$email' AND id != $id";
        $resultadoEmail = $mysql->efectuarConsulta($consultaEmail);

        if (mysqli_num_rows($resultadoEmail) > 0) {
            echo json_encode(["status" => "duplicado"]);
            exit();
        }

        $consulta = "UPDATE usuario SET nombre = '$nombre', apellido = '$apellido', email = '$email', tipo = '$tipo'  WHERE id = $id";


        $resultado = $mysql->efectuarConsulta($consulta);
        if ($resultado) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al editar usuario"]);
        }
        //* eliminar 
    } elseif ($_POST['accion'] == 'eliminar') {
        $id = $_POST['id'];

        // * desactivar el usuario
        $consultaUsuario = "UPDATE usuario SET activo = 'inactivo' WHERE id = $id";
        $resultadoUsuario = $mysql->efectuarConsulta($consultaUsuario);

        //* obtener los libros de las reservas pendientes y aprobadas antes de rechazarlas
        $consultaLibrosReservas = "SELECT id_libro, COUNT(*) as cantidad FROM reserva WHERE id_usuario = $id AND estado IN ('pendiente', 'aprobada') GROUP BY id_libro";
        $resultadoLibros = $mysql->efectuarConsulta($consultaLibrosReservas);

        //* actualizar el inventario de cada libro
        while ($fila = mysqli_fetch_assoc($resultadoLibros)) {
            $idLibro = $fila['id_libro'];
            $cantidadDevolver = $fila['cantidad'];

            //* sumar la cantidad al inventario actual
            $consultaActualizarLibro = "UPDATE libro SET cantidad = cantidad + $cantidadDevolver,disponibilidad = 'Disponible'WHERE id = $idLibro";
            $mysql->efectuarConsulta($consultaActualizarLibro);
        }

        //* despues rechazar las reservas pendientes o aprobadas
        $consultaReservas = "UPDATE reserva SET estado = 'rechazada' WHERE id_usuario = $id AND estado IN ('pendiente', 'aprobada')";
        $mysql->efectuarConsulta($consultaReservas);


        if ($resultadoUsuario) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al desactivar usuario"]);
        }
    } elseif ($_POST['accion'] == 'editar_perfil') {
        $id = $_POST['id'];
        $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_SPECIAL_CHARS);
        $apellido = filter_var($_POST['apellido'], FILTER_SANITIZE_SPECIAL_CHARS);
        $email = $_POST['email'];
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        $consultaEmail = "SELECT id FROM usuario WHERE email = '$email' AND id != $id";
        $resultadoEmail = $mysql->efectuarConsulta($consultaEmail);

        if (mysqli_num_rows($resultadoEmail) > 0) {
            echo json_encode(["status" => "duplicado"]);
            exit();
        }

        $consulta = "UPDATE usuario SET nombre = '$nombre', apellido = '$apellido', email = '$email'";
        if ($password != "") {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $consulta .= ", contrasena = '$password_hash'";
        }
        $consulta .= " WHERE id = $id";

        $resultado = $mysql->efectuarConsulta($consulta);

        if ($resultado) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al editar perfil"]);
        }
    }
}
$mysql->desconectar();
