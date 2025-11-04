<?php
// Limpiar cualquier salida previa
ob_start();

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once '../models/MySQL.php';
require_once '../vendor/phpspreadsheet/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

$mysql = new MySQL();
$mysql->conectar();

$tipo = $_GET['tipo'];
$fecha_inicio = $_GET['fecha_inicio'];
$fecha_fin = $_GET['fecha_fin'];

//* variales que cambian pedendiendo del informe
$titulo = "";
$consulta = "";

//* Inventario de libros
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

//* préstamos realizados
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

    if (!empty($fecha_inicio) && !empty($fecha_fin)) {
        $consulta .= " WHERE prestamo.fecha_prestamo BETWEEN '$fecha_inicio' AND '$fecha_fin' and prestamo.fecha_devolucion BETWEEN '$fecha_inicio' AND '$fecha_fin'";
    }

    $consulta .= " ORDER BY prestamo.fecha_prestamo DESC";
}

//* reservas realizadas
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

    if (!empty($fecha_inicio) && !empty($fecha_fin)) {
        $consulta .= " WHERE reserva.fecha_reserva BETWEEN '$fecha_inicio' AND '$fecha_fin'";
    }

    $consulta .= " ORDER BY reserva.fecha_reserva DESC";
}

//* libros más prestados
elseif ($tipo == "mas_prestados") {
    $titulo = "LIBROS MAS PRESTADOS";
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

//* usuarios registrados
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

else {
    exit("Tipo de informe no válido.");
}

//* ejecutar la consulta
$resultado = $mysql->efectuarConsulta($consulta);

//* crear el objeto Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

//* configurar título principal
$sheet->setCellValue('A1', 'Biblioteca Sena - Informe General');
$sheet->mergeCells('A1:E1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Configurar subtítulo
$sheet->setCellValue('A2', $titulo);
$sheet->mergeCells('A2:E2');
$sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

//* verificar si hay datos
$primeraFila = mysqli_fetch_assoc($resultado);
if (!$primeraFila) {
    $sheet->setCellValue('A4', 'No hay datos disponibles.');
    
    // Guardar y enviar el archivo
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="informe_' . $tipo . '.xlsx"');
    header('Cache-Control: max-age=0');
    
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    
    $mysql->desconectar();
    exit();
}

//* volver al inicio del resultado
mysqli_data_seek($resultado, 0);

//* encabezados de columna (fila 4)
$columnas = array_keys($primeraFila);
$columnaLetra = 'A';
foreach ($columnas as $columna) {
    $nombreColumna = str_replace("_", " ", $columna);
    $sheet->setCellValue($columnaLetra . '4', $nombreColumna);
    $columnaLetra++;
}

//* estilo para encabezados
$ultimaColumna = chr(ord('A') + count($columnas) - 1);
$sheet->getStyle('A4:' . $ultimaColumna . '4')->applyFromArray([
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF']
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '0BDA51']
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN
        ]
    ]
]);

// Llenar datos
$fila = 5;
while ($datos = mysqli_fetch_assoc($resultado)) {
    $columnaLetra = 'A';
    foreach ($datos as $valor) {
        $sheet->setCellValue($columnaLetra . $fila, $valor);
        $columnaLetra++;
    }
    $fila++;
}

//* aplicar bordes a todas las celdas con datos
$ultimaFila = $fila - 1;
$sheet->getStyle('A4:' . $ultimaColumna . $ultimaFila)->applyFromArray([
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => '000000']
        ]
    ]
]);

//* centrar contenido de las celdas de datos
$sheet->getStyle('A5:' . $ultimaColumna . $ultimaFila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

//* ajustar ancho de columnas automáticamente
foreach (range('A', $ultimaColumna) as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

//* desconectar de la base de datos
$mysql->desconectar();

//* limpiar el buffer de salida
ob_end_clean();

//* eviar el archivo al navegador
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="informe_' . $tipo . '.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: cache, must-revalidate');
header('Pragma: public');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();