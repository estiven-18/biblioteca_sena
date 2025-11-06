<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();


if (isset($_POST['email']) && isset($_POST['password'])) {

    $email = $_POST['email'];
    $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];


    $consulta = "SELECT * FROM usuario WHERE email = '$email'";
    $resultado = $mysql->efectuarConsulta($consulta);
    $usuario = mysqli_fetch_assoc($resultado);

    if ($usuario && password_verify($password, $usuario['contrasena'])) {
        
        if ($usuario['activo'] != "activo") {
            echo json_encode(["status" => "inactivo", "message" => "Tu cuenta está inactiva"]);
            $mysql->desconectar();
            exit();
        }


        $_SESSION['id_usuario'] = $usuario['id'];
        $_SESSION['tipo_usuario'] = $usuario['tipo'];
        $_SESSION['activo'] = $usuario['activo'];

        echo json_encode(["status" => "success"]);
        
    } else {
        echo json_encode(["status" => "error", "message" => "email o contraseña incorrectos"]);
    }
    $mysql->desconectar();
    exit();
}