<?php
header('Content-Type: application/json');
require_once '../models/MySQL.php';

$mysql = new MySQL();
$mysql->conectar();

$tipo = $_GET['tipo'];

//* dependiendo del tipo se hace una consulta 
if ($tipo === 'libros') {
    //* esto grafica libros 
    $consulta = "
    SELECT 
        COUNT(*) AS count, 
        'Libros' AS label
    FROM libro
";
} elseif ($tipo === 'reservas') {
    //* esto grafica reservas  
    $consulta = "
        SELECT 
        COUNT(*) AS count, 
        'Reservas' AS label
    FROM reserva 
    ";
} elseif ($tipo === 'usuarios') {
    //* esto grafica usuarios 
    $consulta = "
        SELECT tipo AS label, 
        COUNT(*) AS count 
        FROM usuario 
        GROUP BY tipo 
        ORDER BY tipo;
    ";
} else {
    echo json_encode(['error' => 'Tipo no válido']);
    exit;
}

$resultado = $mysql->efectuarConsulta($consulta);

//* arreglo que se va a convertir en JSON para la cuenta y los labels
$data = ['labels' => [], 'counts' => []];

while ($fila = mysqli_fetch_assoc($resultado)) {
    $data['labels'][] = $fila['label'];
    $data['counts'][] = $fila['count'];
}

$mysql->desconectar();

echo json_encode($data);
