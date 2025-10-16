<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Libro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Agregar Nuevo Libro</h2>
        <form id="formAgregarLibro" class="mt-3">
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" class="form-control" id="titulo" placeholder="Título" required>
            </div>

            <div class="mb-3">
                <label for="autor" class="form-label">Autor</label>
                <input type="text" class="form-control" id="autor" placeholder="Autor" required>
            </div>

            <div class="mb-3">
                <label for="ISBN" class="form-label">ISBN</label>
                <input type="text" class="form-control" id="ISBN" placeholder="ISBN" required>
            </div>

            <div class="mb-3">
                <label for="categoria" class="form-label">Categoría</label>
                <input type="text" class="form-control" id="categoria" placeholder="Categoría" required>
            </div>

            <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" class="form-control" id="cantidad" placeholder="Cantidad" min="1" required>
            </div>

            <div class="mb-3">
                <label for="disponibilidad" class="form-label">Disponibilidad</label>
                <select class="form-select" id="disponibilidad" required>
                    <option value="Disponible">Disponible</option>
                    <option value="No disponible">No disponible</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Agregar Libro</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/scripts.js"></script>
</body>
</html>
