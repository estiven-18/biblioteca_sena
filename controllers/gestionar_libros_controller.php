<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}


require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

//* dependiendo de la accion que venga del front, se agrega, edita o elimina un libro
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion'])) {
    $accion = $_POST['accion'];

    //* agregar libro
    if ($accion == 'agregar') {
        $titulo = filter_var($_POST['titulo'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $autor = filter_var($_POST['autor'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $ISBN = filter_var($_POST['ISBN'], FILTER_SANITIZE_NUMBER_INT);
        $categoria = filter_var($_POST['categoria'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $cantidad = filter_var($_POST['cantidad'], FILTER_SANITIZE_NUMBER_INT);
        $disponibilidad = filter_var($_POST['disponibilidad'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        
        $verificarISBN = "SELECT id FROM libro WHERE ISBN = '$ISBN'";
        $resultadoVerificacion = $mysql->efectuarConsulta($verificarISBN);

        if ($resultadoVerificacion && mysqli_num_rows($resultadoVerificacion) > 0) {
            echo json_encode(["status" => "error", "message" => "El ISBN ya existe."]);
            exit; 
        }

        //*para verificar que el isbn no es repetido
        $consulta = "INSERT INTO libro (titulo, autor, ISBN, categoria, cantidad, disponibilidad) VALUES ('$titulo', '$autor', '$ISBN', '$categoria', $cantidad, '$disponibilidad')";
        $resultado = $mysql->efectuarConsulta($consulta);

        if ($resultado) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al agregar el libro"]);
        }



    } elseif ($accion == 'editar') {


        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $titulo = filter_var($_POST['titulo'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $autor = filter_var($_POST['autor'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        // $ISBN = filter_var($_POST['ISBN'], FILTER_SANITIZE_NUMBER_INT);
        $categoria = filter_var($_POST['categoria'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $disponibilidad = filter_var($_POST['disponibilidad'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $cantidad = filter_var($_POST['cantidad'], FILTER_SANITIZE_NUMBER_INT);

        $consulta = "UPDATE libro SET titulo='$titulo', autor='$autor', categoria='$categoria',disponibilidad='$disponibilidad', cantidad=$cantidad WHERE id=$id";
        $resultado = $mysql->efectuarConsulta($consulta);

        if ($resultado) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al editar el libro"]);
        }

        
        //* eliminar libro
    } elseif ($accion == 'eliminar') {

        $id = $_POST['id'];

        $consulta = "UPDATE LIBRO set disponibilidad='No disponible'   WHERE id=$id";
        $resultado = $mysql->efectuarConsulta($consulta);

        if ($resultado) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al eliminar el libro"]);
        }
    }
}

$mysql->desconectar();