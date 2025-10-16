//* Script para el formulario de registro
$(document).ready(function () {
  $("#formRegistro").submit(function (e) {
    e.preventDefault();
    let datos = {
      nombre: $("#nombre").val(),
      apellido: $("#apellido").val(),
      email: $("#email").val(),
      password: $("#password").val(),
    };
    $.ajax({
      url: "../controllers/registro_empleado_controller.php",
      type: "POST",
      data: datos,
      dataType: "json",
      success: function (respuesta) {
        if (respuesta.status === "success") {
          alert("Registrado");
          window.location.href = "login.php";
        } else {
          alert(respuesta.message);
        }
      },
    });
  });
});

//* Script para el formulario de login
$(document).ready(function () {
  $("#formLogin").submit(function (e) {
    e.preventDefault();
    let datos = {
      email: $("#email").val(),
      password: $("#password").val(),
    };
    $.ajax({
      url: "../controllers/login_controller.php",
      type: "POST",
      data: datos,
      dataType: "json",
      success: function (respuesta) {
        if (respuesta.status === "success") {
          alert("Login Bueno");
          window.location.href = "dashboard.php";
        } else {
          alert(respuesta.message);
        }
      },
    });
  });
});

//* Script para el formulario de agregar libro
$(document).ready(function () {
  $("#formAgregarLibro").submit(function (e) {
    e.preventDefault();

    let datos = {
      titulo: $("#titulo").val(),
      autor: $("#autor").val(),
      ISBN: $("#ISBN").val(),
      categoria: $("#categoria").val(),
      cantidad: $("#cantidad").val(),
      disponibilidad: $("#disponibilidad").val(),
    };

    $.ajax({
      url: "../controllers/libro_agregar_controller.php",
      type: "POST",
      data: { accion: "agregar", ...datos },
      dataType: "json",
      success: function (respuesta) {
        if (respuesta.status === "success") {
          alert("Libro agregado Corretamente!");
          window.location.href = "gestionar_libros.php";
        } else {
          alert(respuesta.message || "Error al agregar libro");
        }
      },
    });
  });
});

