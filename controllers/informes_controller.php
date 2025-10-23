<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}


require_once '../models/MySQL.php';
require_once '../fpdf/fpdf.php';

$mysql = new MySQL();
$mysql->conectar();

$tipo = $_GET['tipo'] ;
$fecha_inicio = $_GET['fecha_inicio'];
$fecha_fin = $_GET['fecha_fin'];

//todo    $fecha_inicio = $_GET['fecha_inicio'] ?? null;
//todo   $fecha_fin = $_GET['fecha_fin'] ?? null;

//todo  if (!$tipo) {
//todo    exit("Faltan parámetros.");
//todo  }

class PDF extends FPDF {
    //* esta clase es para el encabezado y pie de pagina del pdf
    function Header() {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, utf8_decode('Biblioteca Pública - Informe General'), 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

//* estan vacias porque se llenan dependiendo del tipo de informe
$titulo = "";
$consulta = "";


//* inventario de libros
//* lo que muestra es el inventario de libros con su titulo,autor,categoria,cantidad y disponibilidad

if ($tipo == "inventario") {
    $titulo = "INVENTARIO DE LIBROS";
    $consulta = "
        SELECT 
            libro.titulo AS Titulo,
            libro.autor AS Autor,
            libro.categoria AS Categoria,
            libro.cantidad AS Cantidad,
            libro.disponibilidad AS Disponibilidad
        FROM libro
        ORDER BY libro.titulo ASC
    ";
}

//* préstamos realizados, lo que muestra son los prestamos realizados entre dos fechas

elseif ($tipo == "prestamos") {
    $titulo = "HISTORIAL DE PRÉSTAMOS";
    $consulta = "
        SELECT 
            usuario.nombre AS Nombre_Usuario,
            usuario.apellido AS Apellido_Usuario,
            libro.titulo AS Titulo_Libro,
            prestamo.fecha_prestamo AS Fecha_Prestamo,
            prestamo.fecha_devolucion AS Fecha_Devolucion
        FROM prestamo
         JOIN reserva ON prestamo.id_reserva = reserva.id
         JOIN usuario ON reserva.id_usuario = usuario.id
         JOIN libro ON reserva.id_libro = libro.id
    ";

    //* si las fechas no estan vacias, filtra por las fechas enviadas

    if (!empty($fecha_inicio) && !empty($fecha_fin)) {
        //* se concecta la consulta con las fechas enviadas
        $consulta .= " WHERE prestamo.fecha_prestamo BETWEEN '$fecha_inicio' AND '$fecha_fin' and prestamo.fecha_devolucion BETWEEN '$fecha_inicio' AND '$fecha_fin'";
    }

    //y luego ordena por fecha de prestamo descendente
    $consulta .= " ORDER BY prestamo.fecha_prestamo DESC";
}

//* reservas realizadas, lo que muestra son las reservas realizadas entre dos fechas
elseif ($tipo == "reservas") {
    $titulo = "HISTORIAL DE RESERVAS";
    $consulta = "
        SELECT 
            usuario.nombre AS Nombre_Usuario,
            usuario.apellido AS Apellido_Usuario,
            libro.titulo AS Titulo_Libro,
            reserva.fecha_reserva AS Fecha_Reserva,
            reserva.estado AS Estado
        FROM reserva
         JOIN usuario ON reserva.id_usuario = usuario.id
         JOIN libro ON reserva.id_libro = libro.id
    ";

    //* si las fechas no estan vacias, filtra por las fechas enviadas
    if (!empty($fecha_inicio) && !empty($fecha_fin)) {
        //* se concecta la consulta con las fechas enviadas
        $consulta .= " WHERE reserva.fecha_reserva BETWEEN '$fecha_inicio' AND '$fecha_fin'";
    }

    //* luego ordena por fecha de reserva descendente
    $consulta .= " ORDER BY reserva.fecha_reserva DESC";
}

//* libros más prestados, lo que muestra son los 10 libros mas prestados

elseif ($tipo == "mas_prestados") {
    $titulo = "LIBROS MÁS PRESTADOS";
    $consulta = "
        SELECT 
            libro.titulo AS Titulo,
            libro.autor AS Autor,
            COUNT(prestamo.id) AS Veces_Prestado
        FROM prestamo
        JOIN reserva ON prestamo.id_reserva = reserva.id
        JOIN libro ON reserva.id_libro = libro.id
        GROUP BY libro.id, libro.titulo, libro.autor
        ORDER BY Veces_Prestado DESC
        LIMIT 10
    ";
}

//* usuarios registrados, lo que muestra son los usuarios registrados 
elseif ($tipo == "usuarios") {
    $titulo = "USUARIOS REGISTRADOS";
    $consulta = "
        SELECT 
            usuario.id AS ID,
            usuario.nombre AS Nombre,
            usuario.apellido AS Apellido,
            usuario.email AS Correo,
            usuario.tipo AS Tipo_Usuario
        FROM usuario
        ORDER BY usuario.id ASC
    ";
}

//* verificar si el tipo de informe es válido sino mostrar un mensaje de error
//* se pude quitar porque ya se valida en el front pero por si acaso
else {
    exit("Tipo de informe no válido.");
}

//*ejecutar la consulta dependiendo del tipo de informe, es decir del if donde entro

$resultado = $mysql->efectuarConsulta($consulta);

//* crear el PDF y agregar el contenido
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode($titulo), 0, 1, 'C');
$pdf->Ln(8);

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(200, 220, 255);

//* verificar si hay datos para mostrar
$primeraFila = mysqli_fetch_assoc($resultado);
//* si no hay datos, mostrar un mensaje en el pdf
if (!$primeraFila) {
    $pdf->Cell(0, 10, 'No hay datos disponibles.', 1, 1, 'C');
    $pdf->Output("I", "informe_$tipo.pdf");
    $mysql->desconectar();
    exit();
}

//* esto es para volver al inicio del resultado despues de haber obtenido la primera fila
mysqli_data_seek($resultado, 0);

//* Encabezados de columna
foreach ($primeraFila as $columna => $valor) {
    //* lo que esta haciendo es reemplazar los guiones bajos por espacios en los nombres de las columnas
    $pdf->Cell(40, 8, utf8_decode(str_replace("_", " ", $columna)), 1, 0, 'C', true);
}
$pdf->Ln();

//* lo que muestra los datos del resultado de la consulta
$pdf->SetFont('Arial', '', 9);
while ($fila = mysqli_fetch_assoc($resultado)) {
    foreach ($fila as $valor) {
        $pdf->Cell(40, 8, utf8_decode($valor), 1, 0, 'C');
    }
    $pdf->Ln();
}

$mysql->desconectar();
$pdf->Output("I", "informe_$tipo.pdf");
exit();
?>
