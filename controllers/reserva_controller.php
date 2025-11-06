<?php
//* se necesita para enviar correos, esto es para cuando se aprueba una reserva o se crea un prestamo
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//* sirve para cargar las librerias de PHPMailer 
require '../vendor/PHPMailer/src/Exception.php';
require '../vendor/PHPMailer/src/PHPMailer.php';
require '../vendor/PHPMailer/src/SMTP.php';

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
        //* verificar si el libro está disponible  porque si no no se puede reservar
        $consultaDisponibilidad = "SELECT disponibilidad, cantidad FROM libro WHERE id = $id_libro";
        $resultadoDisponibilidad = $mysql->efectuarConsulta($consultaDisponibilidad);
        $libro = mysqli_fetch_assoc($resultadoDisponibilidad);
        //* si el libro esta disponible se puede reservar
       if ($libro['disponibilidad'] == 'Disponible' && $libro['cantidad'] > 0) {
            //* crear la reserva
            $consulta = "INSERT INTO reserva (id_usuario, id_libro, fecha_reserva) VALUES ($id_usuario, $id_libro, '$fecha_reserva')";
            $resultado = $mysql->efectuarConsulta($consulta);
            
            if ($resultado) {
                //* restar 1 a la cantidad del libro
                $mysql->efectuarConsulta("UPDATE libro SET cantidad = cantidad - 1 WHERE id = $id_libro");
                
                //* si la cantidad llega a 0, cambiar disponibilidad a 'No disponible'
                $mysql->efectuarConsulta("UPDATE libro SET disponibilidad = 'No disponible' WHERE id = $id_libro AND cantidad = 0");
                
                echo json_encode(["status" => "success"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error al reservar"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Libro no disponible o sin existencias"]);
        }
    } elseif ($_POST['accion'] == 'aprobar') {
        $id = $_POST['id'];
        //* lo que hace es que pone la reserva como aprobada para que se cree el boton de crear en gestionar_reservas.php
        $consulta = "UPDATE reserva SET estado = 'aprobada' WHERE id = $id";
        $resultado = $mysql->efectuarConsulta($consulta);
        if ($resultado) {
            //* enviar email de confirmación
            $consultaEmail = "SELECT usuario.email, libro.titulo FROM reserva JOIN usuario ON reserva.id_usuario = usuario.id JOIN libro ON reserva.id_libro = libro.id WHERE reserva.id = $id";
            $resultadoEmail = $mysql->efectuarConsulta($consultaEmail);

            //? lo que hace es obtener el email y el titulo del libro para enviarlo en el correo
            $datos = mysqli_fetch_assoc($resultadoEmail);

            //*esto llama a la funcion de enviar email que esta mas abajo
            //* 'email' y 'titulo' son los campos que se obtienen en la consulta anterior
            //* $datos['email'] es el email del usuario que hizo la reserva al ugual que el titulo del libro
            enviarEmail($datos['email'], "Reserva Aprobada", "Tu reserva para el libro '{$datos['titulo']}' ha sido aprobada.");
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al aprobar"]);
        }
    } elseif ($_POST['accion'] == 'rechazar') {
        $id = $_POST['id'];
        
        //* obtener el id del libro antes de rechazar para devolver la cantidad
        $consultaLibro = "SELECT id_libro FROM reserva WHERE id = $id";
        $resultadoLibro = $mysql->efectuarConsulta($consultaLibro);
        $reserva = mysqli_fetch_assoc($resultadoLibro);
        $id_libro = $reserva['id_libro'];
        
        //* rechazar la reserva
        $consulta = "UPDATE reserva SET estado = 'rechazada' WHERE id = $id";
        $resultado = $mysql->efectuarConsulta($consulta);
        
        if ($resultado) {
            //* devolver 1 libro a la cantidad 
            $mysql->efectuarConsulta("UPDATE libro SET cantidad = cantidad + 1 WHERE id = $id_libro");
            
            //* si había cantidad 0 y ahora hay 1 o más, cambiar a 'Disponible'
            $mysql->efectuarConsulta("UPDATE libro SET disponibilidad = 'Disponible' WHERE id = $id_libro AND cantidad > 0");
            
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al rechazar"]);
        }
    } elseif ($_POST['accion'] == 'crear_prestamo') {
        
        //* lo que hace es que crea el prestamo con la fecha actual y la fecha de devolucion es 10 dias despues
        $id_reserva = $_POST['id_reserva'];
        $fecha_prestamo = date('Y-m-d H:i:s');
        //* esto lo que hace es sumar 10 dias a la fecha actual
        $fecha_devolucion = date('Y-m-d', strtotime('+10 days'));

        $consulta = "INSERT INTO prestamo (id_reserva, fecha_prestamo, fecha_devolucion) VALUES ($id_reserva, '$fecha_prestamo', '$fecha_devolucion')";
        $resultado = $mysql->efectuarConsulta($consulta);

        
        $consulta = "UPDATE reserva SET estado = 'creado' WHERE id = $id_reserva";
        $resultado = $mysql->efectuarConsulta($consulta);
        if ($resultado) {
            // ! revisar porque si hay mas de un libro de igual forma lo pone como no disponible sabiendo que puede haber mas de un libro
             // ! revisar porque si hay mas de un libro de igual forma lo pone como no disponible sabiendo que puede haber mas de un libro
              // ! revisar porque si hay mas de un libro de igual forma lo pone como no disponible sabiendo que puede haber mas de un libro
               // ! revisar porque si hay mas de un libro de igual forma lo pone como no disponible sabiendo que puede haber mas de un libro
            $mysql->efectuarConsulta("UPDATE libro SET disponibilidad = 'No disponible' WHERE id = (SELECT id_libro FROM reserva WHERE id = $id_reserva)");
            //* enviar email de confirmación de prestamo craedo
            $consultaEmail = "SELECT usuario.email, libro.titulo FROM reserva JOIN usuario ON reserva.id_usuario = usuario.id JOIN libro ON reserva.id_libro = libro.id WHERE reserva.id = $id_reserva";
            $resultadoEmail = $mysql->efectuarConsulta($consultaEmail);
            $datos = mysqli_fetch_assoc($resultadoEmail);

            //* los datos que se van a madar
            enviarEmail($datos['email'], "Prestamo Creado", "Tu préstamo para el libro '{$datos['titulo']}' ha sido creado. Fecha de devolución: $fecha_devolucion.");
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al crear préstamo"]);
        }
    }
}

//* serve para enviar correos de confirmacion
function enviarEmail($destinatario, $asunto, $mensaje)
{
    //* crea una nueva instancia de PHPMailer,( una instancia es un objeto creado a partir de una clase)
    $mail = new PHPMailer(true);
    try {
        //* esto es para configurar el servidor SMTP de gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        //! poner los verdadesros
        $mail->Username = 'pruebaadso2025@gmail.com';  //! poner  con tu email
        $mail->Password = 'aypi xyao docb utjv';  //! poner con tu contraseña o App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('pruebaadso2025@gmail.com', 'Biblioteca Sena');
        $mail->addAddress($destinatario);

        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $mensaje;

        $mail->send();
    } catch (Exception $e) {
        echo "Error al enviar correo: {$mail->ErrorInfo}";
    }
}

$mysql->desconectar();