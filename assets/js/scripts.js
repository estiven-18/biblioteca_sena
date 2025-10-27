//* registrar empleado
$(document).ready(function () {
  $("#formRegistro").submit(function (e) {
    e.preventDefault();
    //* es un array poruque son varios datos los que se envian al controlador
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

//* login empleado 
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

//* datatable libros 
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

//*datatable reservas - lo que ve el empleado
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

//* datatable mis reservas - lo que ve el usuario
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

//*devolver libro- ya lo entrego
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


//* datatable de prestamos
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

//* generar informe PDF
$("#btnPDF").on("click", function () {
  let tipo = $("#tipo_informe").val();
  let fecha_inicio = $("#fecha_inicio").val();
  let fecha_fin = $("#fecha_fin").val();
  let url = `../controllers/informes_controller.php?tipo=${tipo}&fecha_inicio=${fecha_inicio}&fecha_fin=${fecha_fin}`;
  window.open(url, "_blank");
});

//* crear usuario
$(document).ready(function () {
  $("#formCrearUsuario").submit(function (e) {
    e.preventDefault();
    let datos = {
      nombre: $("#nombre").val(),
      apellido: $("#apellido").val(),
      email: $("#email").val(),
      password: $("#password").val(),
      tipo: $("#tipo").val(),
    };
    $.ajax({
      url: "../controllers/usuario_controller.php",
      type: "POST",
      data: { accion: "crear", ...datos },
      dataType: "json",
      success: function (respuesta) {
        if (respuesta.status === "success") {
          alert("Usuario creado exitosamente!");
          window.location.href = "gestionar_usuarios.php";
        } else {
          alert(respuesta.message);
        }
      },
    });
  });
});

//* editar usuario
$(document).ready(function () {
  $("#formEditarUsuario").submit(function (e) {
    e.preventDefault();
    let datos = {
      id: $("#id").val(),
      nombre: $("#nombre").val(),
      apellido: $("#apellido").val(),
      email: $("#email").val(),
      tipo: $("#tipo").val(),
      password: $("#password").val(), // Asegúrate de que esté incluido
    };
    $.ajax({
      url: "../controllers/usuario_controller.php",
      type: "POST",
      data: { accion: "editar", ...datos },
      dataType: "json",
      success: function (respuesta) {
        if (respuesta.status === "success") {
          alert("Usuario editado exitosamente!");
          window.location.href = "gestionar_usuarios.php";
        } else {
          alert(respuesta.message);
        }
      },
    });
  });
});
//* eliminar usuario
$(document).ready(function () {
  $(".btnEliminarUsuario").on("click", function () {
    let id = $(this).data("id");
    if (confirm("¿Estás seguro de eliminar este usuario?")) {
      $.ajax({
        url: "../controllers/usuario_controller.php",
        type: "POST",
        data: { accion: "eliminar", id: id },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta.status === "success") {
            alert("Usuario eliminado!");
            location.reload();
          } else {
            alert(respuesta.message);
          }
        },
      });
    }
  });
});


//* crear préstamo de reserva
$(document).ready(function () {
  $(".btnCrearPrestamo").on("click", function () {
    let id_reserva = $(this).data("id");
    $.ajax({
      url: "../controllers/reserva_controller.php",
      type: "POST",
      data: { accion: "crear_prestamo", id_reserva: id_reserva },
      dataType: "json",
      success: function (respuesta) {
        if (respuesta.status === "success") {
          alert("Préstamo creado exitosamente!");
          location.reload();
        } else {
          alert(respuesta.message);
        }
      },
    });
  });
});


//* crear reserva
$(document).ready(function () {
  $("#formCrearReserva").submit(function (e) {
    e.preventDefault();
    let datos = {
      id_usuario: $("#id_usuario").val(),
      id_libro: $("#id_libro").val(),
    };
    $.ajax({
      url: "../controllers/reserva_controller.php",
      type: "POST",
      data: { accion: "crear", ...datos },
      dataType: "json",
      success: function (respuesta) {
        if (respuesta.status === "success") {
          alert("Reserva creada exitosamente!");
          window.location.href = "gestionar_reservas.php";
        } else {
          alert(respuesta.message);
        }
      },
    });
  });
});

//* aprobar reserva
$(document).ready(function () {
  $(".btnAprobar").on("click", function () {
    let id = $(this).data("id");
    $.ajax({
      url: "../controllers/reserva_controller.php",
      type: "POST",
      data: { accion: "aprobar", id: id },
      dataType: "json",
      success: function (respuesta) {
        if (respuesta.status === "success") {
          alert("Reserva aprobada!");
          location.reload();
        } else {
          alert(respuesta.message);
        }
      },
    });
  });
});

//*rechazar reserva
$(document).ready(function () {
  $(".btnRechazar").on("click", function () {
    let id = $(this).data("id");
    $.ajax({
      url: "../controllers/reserva_controller.php",
      type: "POST",
      data: { accion: "rechazar", id: id },
      dataType: "json",
      success: function (respuesta) {
        if (respuesta.status === "success") {
          alert("Reserva rechazada!");
          location.reload();
        } else {
          alert(respuesta.message);
        }
      },
    });
  });
});

//* boton logout
$(document).ready(function () {
  $(".btnLogout").on("click", function (e) {
    e.preventDefault();

    Swal.fire({
      title: "¿Cerrar sesión?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Cerrar sesión",
      cancelButtonText: "Cancelar",
      reverseButtons: true,
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "../controllers/logout_controller.php",

          type: "POST",
          success: function () {
            Swal.fire({
              icon: "success",
              title: "Sesión cerrada",
              timer: 2000,
              showConfirmButton: false,
            }).then(() => {
              window.location.href = "../views/login.php";
            });
          },
          error: function () {
            Swal.fire("Error", "No se pudo cerrar la sesión", "error");
          },
        });
      }
    });
  });
});

//* editar perfil pero el que lo hace el usuario
$(document).ready(function () {
  $("#formEditarPerfil").submit(function (e) {
    e.preventDefault();
    let datos = {
      id: $("#id").val(),
      nombre: $("#nombre").val(),
      apellido: $("#apellido").val(),
      email: $("#email").val(),
      password: $("#password").val(),
    };
    $.ajax({
      url: "../controllers/usuario_controller.php",
      type: "POST",
      data: { accion: "editar_perfil", ...datos },
      dataType: "json",
      success: function (respuesta) {
        if (respuesta.status === "success") {
          alert("Perfil actualizado exitosamente!");
          window.location.href = "perfil.php";
        } else {
          alert(respuesta.message);
        }
      },
    });
  });
});

//* tabla de historial de reservas
$(document).ready(function () {
  $("#tablaHistorialReservas").DataTable({
    language: {
      url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json",
    },
    responsive: true,
  });
});

//* tabla de historial de prestamos
$(document).ready(function () {
  $("#tablaHistorialPrestamos").DataTable({
    language: {
      url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json",
    },
    responsive: true,
  });
});