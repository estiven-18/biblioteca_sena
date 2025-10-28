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
        $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $apellido = filter_var($_POST['apellido'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = $_POST['email'];
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $tipo = $_POST['tipo'];

        $consulta = "INSERT INTO usuario (nombre, apellido, email, contrasena, tipo) VALUES ('$nombre', '$apellido', '$email', '$password', '$tipo')";
        $resultado = $mysql->efectuarConsulta($consulta);
        if ($resultado) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al crear usuario"]);
        }
        //* editar el usuario pero el admin lo edita
    } elseif ($_POST['accion'] == 'editar') {
        $id = $_POST['id'];
        $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $apellido = filter_var($_POST['apellido'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = $_POST['email'];
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        $tipo = $_POST['tipo'];
        $password = $_POST['password'];

        $consulta = "UPDATE usuario SET nombre = '$nombre', apellido = '$apellido', email = '$email', tipo = '$tipo'";
        //* se pone empty para que no de error si no se envia nada en el campo password
        if ($password != "") {

            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            //* tiene el . para concatenar la consulta por si se envia el password
            $consulta .= ", contrasena = '$password_hash'";
        }
        //* se connecta la consulta con el id del usuario a editar
        $consulta .= " WHERE id = $id";

        $resultado = $mysql->efectuarConsulta($consulta);
        if ($resultado) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al editar usuario"]);
        }
        //* eliminar 
    } elseif ($_POST['accion'] == 'eliminar') {
        $id = $_POST['id'];
        $consulta = "DELETE FROM usuario WHERE id = $id";

        $resultado = $mysql->efectuarConsulta($consulta);
        if ($resultado) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al eliminar usuario"]);
        }
        //? edita el perfil del usuario- es decir el mismo lo hace 
    } elseif ($_POST['accion'] == 'editar_perfil') {
        $id = $_POST['id'];
        $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $apellido = filter_var($_POST['apellido'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = $_POST['email'];
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

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
