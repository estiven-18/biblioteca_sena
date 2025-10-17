<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>agregar libro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h2>Agregar Libro</h2>
        <form id="formAgregarLibro" class="mt-3">
            <div class="mb-3">
                <label for="titulo" class="form-label">titulo</label>
                <input type="text" class="form-control" id="titulo" placeholder="Título" required>
            </div>

            <div class="mb-3">
                <label for="autor" class="form-label">auoto</label>
                <input type="text" class="form-control" id="autor" placeholder="Autor" required>
            </div>

            <div class="mb-3">
                <label for="ISBN" class="form-label">isbnm</label>
                <input type="number" class="form-control" id="ISBN" placeholder="ISBN" required>
            </div>

            <div class="mb-3">
                <label for="categoria" class="form-label">categoria</label>
                <input type="text" class="form-control" id="categoria" placeholder="Categoría" required>
            </div>

            <div class="mb-3">
                <label for="cantidad" class="form-label">cantidad</label>
                <input type="number" class="form-control" id="cantidad" placeholder="Cantidad" min="1" required>
            </div>

            <div class="mb-3">
                <label for="disponibilidad" class="form-label">disponibilidad</label>
                <select class="form-select" id="disponibilidad" required>
                    <option value="Disponible">diponible</option>
                    <option value="No disponible">no disponible</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">agrgarr Libro</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/js/scripts.js"></script>
</body>

</html>