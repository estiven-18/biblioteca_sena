<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

//!--------------------------
//! sanitizar dattos
//!--------------------------
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion'])) {

    if ($_POST['accion'] == 'devolver') {
        $id = $_POST['id'];
        //* lo que hace es que pone el prestamo como devuelto y el libro como disponible 
        //! pero hay que si hay 1 en stock y se debuelve, pues lo que hay que hace es aumentar el stock en 1
        $consulta = "UPDATE prestamo SET estado = 'devuelto' WHERE id = $id";
        $resultado = $mysql->efectuarConsulta($consulta);
        if ($resultado) {
            //* marcar libro como disponible
            $mysql->efectuarConsulta("UPDATE libro SET disponibilidad = 'Disponible' WHERE id = (SELECT id_libro FROM reserva WHERE id = (SELECT id_reserva FROM prestamo WHERE id = $id))");
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al devolver"]);
        }
    }
}
$mysql->desconectar();
