<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'administrador') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Informes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h2>Generación de Informes</h2>
        //! el nombre del formulario no es para nada, nada utiliza formInforme
        <form id="formInforme" class="mt-4">
            <div class="mb-3">
                //* el tipo_informe es lo que se usa en el js para saber que tipo de informe generar
                <label for="tipo_informe" class="form-label">Tipo de Informe</label>
                <select class="form-select" id="tipo_informe" required>
                    //* los values son los tipos que se agarra el js para mandar al controlador
                    <option value="inventario">Inventario de Libros</option>
                    <option value="prestamos">Historial de Préstamos</option>
                    <option value="reservas">Historial de Reservas</option>
                    <option value="mas_prestados">Libros Más Prestados</option>
                    <option value="usuarios">Usuarios Registrados</option>
                </select>

            </div>

            //* rango de fechas para los informes que lo requieran como el de prestamos y reservas(el de reservas solo utiliza la fecha de inicio)
            <div class="row mb-3">
                <div class="col">
                    <label for="fecha_inicio" class="form-label">Desde</label>
                    <input type="date" id="fecha_inicio" class="form-control">
                </div>
                <div class="col">
                    <label for="fecha_fin" class="form-label">Hasta</label>
                    <input type="date" id="fecha_fin" class="form-control">
                </div>
            </div>

            //* cunado se le da click a boton la funcion de js agarra los valores de los campos y genera el informe
            <button type="button" id="btnPDF" class="btn btn-danger">Generar PDF</button>
            //! no hace nada aun---------------------------------------
            <button type="button" id="btnExcel" class="btn btn-success">Exportar Excel</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/js/scripts.js"></script>
</body>

</html>