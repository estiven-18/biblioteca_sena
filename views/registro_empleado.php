<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrarse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .vh-100 {
            min-height: 100vh;
        }

        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
    </style>
</head>

<body>
    <section class="vh-100">
        <div class="container-fluid h-100">
            <div class="row h-100">
                <div class="col-sm-12 col-md-6 text-black d-flex justify-content-center align-items-center">
                    <div class="form-container">
                        <form id="formRegistro" style="width: 23rem;">
                            <div class="text-center mb-4">
                                <i class="fas fa-user-plus fa-2x me-2" style="color: #198754;"></i>
                                <span class="h1 fw-bold mb-0">Registro</span>
                            </div>

                            <h3 class="fw-normal mb-3 pb-3 text-center" style="letter-spacing: 1px;">Crear cuenta</h3>

                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" id="nombre" class="form-control" placeholder="Ingresa tu nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input type="text" id="apellido" class="form-control" placeholder="Ingresa tu apellido" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" id="email" class="form-control" placeholder="ejemplo@correo.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" id="password" class="form-control" placeholder="********" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">Registrarse</button>
                            </div>

                            <p class="mt-4 mb-0 text-center">¿Ya tienes cuenta? <a href="login.php" class="link-success">Inicia sesión aquí</a></p>
                        </form>
                    </div>
                </div>

                <div class="col-md-6 px-0 d-none d-md-block">
                    <img src="../assets/img/imagenLogin.jpg"
                        alt="Imagen de registro" class="w-100 vh-100" style="object-fit: cover; object-position: left;">
                </div>
            </div>
        </div>
    </section>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/js/scripts.js"></script>
</body>

</html>