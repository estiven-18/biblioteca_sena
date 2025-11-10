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

        $consultaUsuario = "UPDATE usuario SET activo = 'inactivo' WHERE id = $id";
        $resultadoUsuario = $mysql->efectuarConsulta($consultaUsuario);

        //* rechazar reservas activas del usuario
        $consultaReservas = "UPDATE reserva SET estado = 'rechazada' WHERE id_usuario = $id ";
        $mysql->efectuarConsulta($consultaReservas);

        //* marcar prestamos activos como devueltos y devolver libros
        //!
        $consultaPrestamos = "UPDATE prestamo SET estado = 'devuelto', fecha_devolucion = CURDATE() WHERE id_reserva IN (SELECT id FROM reserva WHERE id_usuario = $id) AND estado = 'activo'";
        $mysql->efectuarConsulta($consultaPrestamos);

        //* actualizar disponibilidad de libros
        $consultaLibros = "UPDATE libro SET disponibilidad = 'Disponible' WHERE id IN (SELECT id_libro FROM reserva WHERE id_usuario = $id AND id IN (SELECT id_reserva FROM prestamo WHERE estado = 'devuelto'))";
        $mysql->efectuarConsulta($consultaLibros);

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
