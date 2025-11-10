<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion'])) {

    if ($_POST['accion'] == 'devolver') {
        $id = $_POST['id'];
        //* lo que hace es que pone el prestamo como devuelto y el libro como disponible 
        $consulta = "UPDATE prestamo SET estado = 'devuelto', fecha_devolucion = CURDATE() WHERE id = $id";
        $resultado = $mysql->efectuarConsulta($consulta);
        if ($resultado) {

            $mysql->efectuarConsulta("UPDATE libro SET cantidad = cantidad + 1 WHERE id = (SELECT id_libro FROM reserva WHERE id = (SELECT id_reserva FROM prestamo WHERE id = $id))");
            //* marcar libro como disponible
            $mysql->efectuarConsulta("UPDATE libro SET disponibilidad = 'Disponible' WHERE id = (SELECT id_libro FROM reserva WHERE id = (SELECT id_reserva FROM prestamo WHERE id = $id)) AND cantidad > 0
            ");
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al devolver"]);
        }
    }
}
$mysql->desconectar();
