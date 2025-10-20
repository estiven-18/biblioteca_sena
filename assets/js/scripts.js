//* registrar empleado
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
        console.log(respuesta);
        if (respuesta.status === "success") {
          Swal.fire({
            icon: "success",
            title: "Registro exitoso",
            text: "Tu cuenta ha sido creada correctamente",
          }).then(() => {
            window.location.href = "login.php";
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Error en registro",
            text: respuesta.message,
          });
        }
      },
    });
  });
});

//* login
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
        console.log(respuesta);
        if (respuesta.status === "success") {
          Swal.fire({
            icon: "success",
            title: "Bienvenido",
            text: "Inicio de sesión exitoso",
          }).then(() => {
            window.location.href = "dashboard.php";
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Error en login",
            text: respuesta.message,
          });
        }
      },
    });
  });
});

//* agregar libro
$(document).ready(function () {
  $("#formAgregarLibro").submit(function (e) {
    e.preventDefault();
    let datos = {
      accion: "agregar",
      titulo: $("#titulo").val(),
      autor: $("#autor").val(),
      ISBN: $("#ISBN").val(),
      categoria: $("#categoria").val(),
      cantidad: $("#cantidad").val(),
      disponibilidad: $("#disponibilidad").val(),
    };
    $.ajax({
      url: "../controllers/gestionar_libros_controller.php",
      type: "POST",
      data: datos,
      dataType: "json",
      success: function (respuesta) {
        console.log(respuesta);
        if (respuesta.status === "success") {
          Swal.fire({
            icon: "success",
            title: "Éxito",
            text: "Libro agregado exitosamente",
          }).then(() => {
            window.location.href = "gestionar_libros.php";
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: respuesta.message,
          });
        }
      },
    });
  });
});

//* editar libro
$(document).ready(function () {
  $("#formEditarLibro").submit(function (e) {
    e.preventDefault();
    let datos = {
      accion: "editar",
      id: $("#id").val(),
      titulo: $("#titulo").val(),
      autor: $("#autor").val(),
      ISBN: $("#ISBN").val(),
      categoria: $("#categoria").val(),
      disponibilidad: $("#disponibilidad").val(),
      cantidad: $("#cantidad").val(),
    };
    $.ajax({
      url: "../controllers/gestionar_libros_controller.php",
      type: "POST",
      data: datos,
      dataType: "json",
      success: function (respuesta) {
        console.log(respuesta);
        if (respuesta.status === "success") {
          Swal.fire({
            icon: "success",
            title: "Actualizado",
            text: "El libro ha sido editado correctamente",
          }).then(() => {
            window.location.href = "gestionar_libros.php";
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: respuesta.message,
          });
        }
      },
    });
  });
});

//* eliminar libro
$(document).ready(function () {
  $(".btnEliminar").on("click", function () {
    let id = $(this).data("id");

    Swal.fire({
      title: "¿Estás seguro?",
      text: "No podrás revertir esto",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "Sí, eliminar",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "../controllers/gestionar_libros_controller.php",
          type: "POST",
          data: { accion: "eliminar", id: id },
          dataType: "json",
          success: function (respuesta) {
            console.log(respuesta);
            if (respuesta.status === "success") {
              Swal.fire({
                icon: "success",
                title: "Eliminado",
                text: "El libro fue eliminado",
              }).then(() => {
                location.reload();
              });
            } else {
              Swal.fire({
                icon: "error",
                title: "Error",
                text: respuesta.message,
              });
            }
          },
        });
      }
    });
  });
});

//* tabla de  libros
$(document).ready(function () {
  if ($.fn.DataTable) {
    $("#tablaLibros").DataTable({
      language: {
        url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json",
      },
      responsive: true,
    });
  }
});

//* reservar libro
$(document).ready(function () {
  $(".btnReservar").on("click", function () {
    let id_libro = $(this).data("id");
    $.ajax({
      url: "../controllers/reserva_controller.php",
      type: "POST",
      data: { accion: "reservar", id_libro: id_libro },
      dataType: "json",
      success: function (respuesta) {
        if (respuesta.status === "success") {
          alert("Libro reservado exitosamente!");
          location.reload();
        } else {
          alert(respuesta.message);
        }
      },
    });
  });
});

//*tabla reservas lo que ve el admin
$(document).ready(function () {
  if ($.fn.DataTable) {
    $("#tablaReservas").DataTable({
      language: {
        url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json",
      },
      responsive: true,
    });
  }
});

//*tabla reservar lo que ve el usuario
$(document).ready(function () {
  if ($.fn.DataTable) {
    $("#tablaMisReservas").DataTable({
      language: {
        url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json",
      },
      responsive: true,
    });
  }
});



//* Script para devolver libro   
$(document).ready(function () {
  $(".btnDevolver").on("click", function () {
    let id = $(this).data("id");
    if (confirm("¿Marcar como devuelto?")) {
      $.ajax({
        url: "../controllers/prestamo_controller.php",
        type: "POST",
        data: { accion: "devolver", id: id },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta.status === "success") {
            alert("Libro devuelto!");
            location.reload();
          } else {
            alert(respuesta.message);
          }
        },
      });
    }
  });
});



//!acomodar para que se vea como las otras tablas
//* tabla prestamos
$(document).ready(function () {
  if ($.fn.DataTable) {
    $("#tablaPrestamos").DataTable({ 
        language: {
        url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json",
      },        
      responsive: true,
    });
  } 
});
