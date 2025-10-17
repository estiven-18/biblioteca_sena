<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion'])) {
    //! dependiendo de la accion que venga del ajax se hace una cosa o otra
    if ($_POST['accion'] == 'reservar') {
        $id_libro = $_POST['id_libro'];
        $id_usuario = $_SESSION['id_usuario'];

        //! importante verificar si el libro esta disponible para no reservar libros que no lo estan
        $consultaDisponibilidad = "SELECT disponibilidad FROM libro WHERE id = $id_libro";
        $resultadoDisponibilidad = $mysql->efectuarConsulta($consultaDisponibilidad);
        $libro = mysqli_fetch_assoc($resultadoDisponibilidad);

        if ($libro['disponibilidad'] == 'Disponible') {
            $fecha_reserva = date('Y-m-d H:i:s');
            $consulta = "INSERT INTO reserva (id_usuario, id_libro, fecha_reserva) VALUES ($id_usuario, $id_libro, '$fecha_reserva')";
            if ($mysql->efectuarConsulta($consulta)) {
                echo json_encode(["status" => "success"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error al reservar"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Libro no disponible"]);
        }
    } elseif ($_POST['accion'] == 'aprobar') {
        $id = $_POST['id'];
        //! pone libro como no disponible al aprobar
        $consultaLibro = "UPDATE libro SET disponibilidad = 'No disponible' WHERE id = (SELECT id_libro FROM reserva WHERE id = $id)";
        $mysql->efectuarConsulta($consultaLibro);

        $consulta = "UPDATE reserva SET estado = 'aprobada' WHERE id = $id";
        if ($mysql->efectuarConsulta($consulta)) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al aprobar"]);
        }
    } elseif ($_POST['accion'] == 'rechazar') {
        $id = $_POST['id'];
        $consulta = "UPDATE reserva SET estado = 'rechazada' WHERE id = $id";
        if ($mysql->efectuarConsulta($consulta)) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al rechazar"]);
        }
    }
}
$mysql->desconectar();
