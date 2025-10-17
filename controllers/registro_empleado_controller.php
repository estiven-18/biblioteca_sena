<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

if (isset($_POST['nombre']) && isset($_POST['email']) && isset($_POST['password'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    //* la segunda password es la de la base de datos 
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    //*se va a poner por defecto cliente
    $tipo = 'cliente';

    $consulta = "INSERT INTO usuario (nombre, apellido, email, contrasena, tipo) VALUES ('$nombre', '$apellido', '$email', '$password', '$tipo')";
    $resultado = $mysql->efectuarConsulta($consulta);
    if ($resultado) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "rerror al registrar el usuario"]);
    }
    $mysql->desconectar();
    exit();
}
