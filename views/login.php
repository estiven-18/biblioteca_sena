<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
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
                        <form id="formLogin" style="width: 23rem;">
                            <div class="text-center mb-4">
                                <i class="fas fa-crow fa-2x me-3" style="color: #709085;"></i>
                                <span class="h1 fw-bold mb-0">Biblioteca Sena</span>
                            </div>

                            <h3 class="fw-normal mb-3 pb-3 text-center" style="letter-spacing: 1px;">Iniciar Sesión</h3>

                            <div class="form-outline mb-4">
                                <input type="email" id="email" class="form-control form-control-lg" placeholder="Email" required />
                                <label class="form-label" for="email">Correo Electrónico</label>
                            </div>

                            <div class="form-outline mb-4">
                                <input type="password" id="password" class="form-control form-control-lg" placeholder="Contraseña" required />
                                <label class="form-label" for="password">Contraseña</label>
                            </div>

                            <div class="pt-1 mb-4">
                                <button type="submit" class="btn btn-success btn-lg btn-block">Ingresar</button>
                            </div>


                            <p>¿No tienes una cuenta? <a href="registro_empleado.php" class="link-success">Regístrate aquí</a></p>
                        </form>
                    </div>
                </div>

                <div class="col-md-6 px-0 d-none d-md-block">
                    <img src="../assets/img/imagenLogin.jpg"
                        alt="Login image" class="w-100 vh-100" style="object-fit: cover; object-position: left;">
                </div>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/js/scripts.js"></script>
</body>

</html>