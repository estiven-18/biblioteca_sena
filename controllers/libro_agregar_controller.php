<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// // Solo administradores
// if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'administrador') {
//     echo json_encode(["status" => "error", "message" => "Acceso prohibido"]);
//     exit();
// }

require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == "agregar") {

    if (!empty($_POST['titulo']) && !empty($_POST['autor']) && !empty($_POST['ISBN']) && !empty($_POST['categoria']) && !empty($_POST['cantidad']) && !empty($_POST['disponibilidad'])) {

        $titulo = $_POST['titulo'];
        $autor = $_POST['autor'];
        $ISBN = $_POST['ISBN'];
        $categoria = $_POST['categoria'];
        $cantidad = intval($_POST['cantidad']);
        $disponibilidad = $_POST['disponibilidad'];

        $consulta = "INSERT INTO libro (titulo, autor, ISBN, categoria, disponibilidad, cantidad) 
                     VALUES ('$titulo', '$autor', '$ISBN', '$categoria', '$disponibilidad', $cantidad)";

        if ($mysql->efectuarConsulta($consulta)) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al guardar en la base de datos"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Datos incompletos"]);
    }

    $mysql->desconectar();
    exit();
}
