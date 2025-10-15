<?php
//* iniciar sesion y conectar a la base de datos
//!esta decicion es para que la secion no quede abierta y no aparaezca el error de sesion ya iniciada
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2>iniciar sdesion</h2>
        <form id="formLogin">
            <input type="email" id="email" placeholder="Email" required>
            <input type="password" id="password" placeholder="Contraseña" required>
            <button type="submit">Ingresar</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $("#formLogin").submit(function(e) {
            e.preventDefault();
            let datos = {
                email: $("#email").val(),
                password: $("#password").val()
            };
            $.ajax({
                url: "../controllers/login_controller.php",
                type: "POST",
                data: datos,
                dataType: "json",
                success: function(respuesta) {
                    if (respuesta.status === "success") {
                        alert("login exitoso");
                        window.location.href = "dashboard.php";
                    } else {
                        alert(respuesta.message);
                    }
                }
            });
        });
    </script>
</body>

</html>
<!-- se pone al final para que se desconecte despues de cargar la pagina-->
<?php $mysql->desconectar(); ?>