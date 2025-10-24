<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion'])) {
    //* la accion la mando desde el front para saber que hacer
    //* reservar libro
    if ($_POST['accion'] == 'reservar') {
        $id_libro = $_POST['id_libro'];
        $id_usuario = $_SESSION['id_usuario'];
        //* para obtener la fecha y hora actual
        $fecha_reserva = date('Y-m-d H:i:s');
        //* Verificar si el libro está disponible proque si no no se puede reservar
        $consultaDisponibilidad = "SELECT disponibilidad FROM libro WHERE id = $id_libro";
        $resultadoDisponibilidad = $mysql->efectuarConsulta($consultaDisponibilidad);
        $libro = mysqli_fetch_assoc($resultadoDisponibilidad);
        //* si el libro esta disponible se puede reservar
        if ($libro['disponibilidad'] == 'Disponible') {
            $consulta = "INSERT INTO reserva (id_usuario, id_libro, fecha_reserva) VALUES ($id_usuario, $id_libro, '$fecha_reserva')";
            $resultado = $mysql->efectuarConsulta($consulta);
            if ($resultado) {
                echo json_encode(["status" => "success"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error al reservar"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Libro no disponible"]);
        }
        //* aprobar reserva o crear reserva
        // } elseif ($_POST['accion'] == 'crear') {
        //     $id_usuario = $_POST['id_usuario'];
        //     $id_libro = $_POST['id_libro'];
        //     $fecha_reserva = date('Y-m-d H:i:s');
        //     $consulta = "INSERT INTO reserva (id_usuario, id_libro, fecha_reserva) VALUES ($id_usuario, $id_libro, '$fecha_reserva')";
        //     if ($mysql->efectuarConsulta($consulta)) {
        //         echo json_encode(["status" => "success"]);
        //     } else {
        //         echo json_encode(["status" => "error", "message" => "Error al crear reserva"]);
        //     }
        //?--------------------------

        //* aprobar reserva
    } elseif ($_POST['accion'] == 'aprobar') {
        $id = $_POST['id'];
        //* lo que hace es que pone la reserva como aprobada para que se cree el boton de crear en gestionar_reservas.php
        $consulta = "UPDATE reserva SET estado = 'aprobada' WHERE id = $id";
        $resultado = $mysql->efectuarConsulta($consulta);
        if ($resultado) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al aprobar"]);
        }

        //* rechazar reserva
    } elseif ($_POST['accion'] == 'rechazar') {
        //* lo que hace es que pone la reserva como rechazada y no se puede crear el prestamo
        $id = $_POST['id'];
        $consulta = "UPDATE reserva SET estado = 'rechazada' WHERE id = $id";
        $resultado = $mysql->efectuarConsulta($consulta);
        if ($resultado) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al rechazar"]);
        }
        //* crear prestamo
    } elseif ($_POST['accion'] == 'crear_prestamo') {
        //* lo que hace es que crea el prestamo con la fecha actual y la fecha de devolucion es 10 dias despues
        $id_reserva = $_POST['id_reserva'];
        $fecha_prestamo = date('Y-m-d H:i:s');
        //* esto lo que hace es sumar 10 dias a la fecha actual
        $fecha_devolucion = date('Y-m-d', strtotime('+10 days'));

        $consulta = "INSERT INTO prestamo (id_reserva, fecha_prestamo, fecha_devolucion) VALUES ($id_reserva, '$fecha_prestamo', '$fecha_devolucion')";
        $resultado = $mysql->efectuarConsulta($consulta);
        if ($resultado) {
            // ! revisar poruqe si hay mas de un libro de igual forma lo pone como no disponible sabiendo que puede haber mas de un libro
            $mysql->efectuarConsulta("UPDATE libro SET disponibilidad = 'No disponible' WHERE id = (SELECT id_libro FROM reserva WHERE id = $id_reserva)");
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al crear préstamo"]);
        }
    }
}
$mysql->desconectar();
