
//! variavez para todos lo inputs para no hacer lo mismo en todas
$(document).ready(function () {
  //? se selecciona todos los input que sean para nombre y apellido
  //? de todas las vistas
  $("input[type='text']#nombre, input[type='text']#apellido").on(
    "input",
    function () {
      //? se obtinee los valores de los input en los que estamos
      let valor = $(this).val();
      let valorLimpio = valor.replace(/[^a-záéíóúñA-ZÁÉÍÓÚÑ\s]/g, "");
      $(this).val(valorLimpio);
    }
  );

  //* solo email válido
  $("input[type='email']#email").on("input", function () {
    let valor = $(this).val();
    let valorLimpio = valor.replace(/[^a-zA-Z0-9@._-]/g, "");
    $(this).val(valorLimpio);
  });

  //* solo letras, números y espacios
  $("#titulo, #autor").on("input", function () {
    let valor = $(this).val();
    let valorLimpio = valor.replace(/[^a-zA-Z0-9áéíóúñÁÉÍÓÚÑ\s.,:-]/g, "");
    $(this).val(valorLimpio);
  });

  //* solo números y guiones
  $("#ISBN").on("input", function () {
    let valor = $(this).val();
    let valorLimpio = valor.replace(/[^0-9-]/g, "");
    $(this).val(valorLimpio);
  });

  //* solo letras y espacios
  $("#categoria").on("input", function () {
    let valor = $(this).val();
    let valorLimpio = valor.replace(/[^a-záéíóúñA-ZÁÉÍÓÚÑ\s]/g, "");
    $(this).val(valorLimpio);
  });

  //* solo números
  $("#cantidad, #disponibilidad").on("input", function () {
    let valor = $(this).val();
    let valorLimpio = valor.replace(/[^0-9]/g, "");
    $(this).val(valorLimpio);
  });
});

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
        } else if (respuesta.status === "duplicado") {
          Swal.fire({
            icon: "warning",
            title: "Correo ya registrado",
            text: "Este correo ya pertenece a otro usuario.",
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
          Swal.fire({
            icon: "success",
            title: "Libro reservado",
            text: "¡Libro reservado exitosamente!",
            showConfirmButton: false,
            timer: 1200,
          }).then(() => {
            window.location.href = "dashboard.php";
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

    Swal.fire({
      title: "¿Marcar como devuelto?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Sí",
      cancelButtonText: "No",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "../controllers/prestamo_controller.php",
          type: "POST",
          data: { accion: "devolver", id: id },
          dataType: "json",
          success: function (respuesta) {
            if (respuesta.status === "success") {
              Swal.fire({
                icon: "success",
                title: "Libro devuelto",
                text: "El libro fue marcado como devuelto.",
                showConfirmButton: false,
                timer: 1200,
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
          Swal.fire({
            icon: "success",
            title: "Usuario creado",
            text: "¡Usuario creado exitosamente!",
            showConfirmButton: false,
            timer: 1200,
          }).then(() => {
            window.location.href = "gestionar_usuarios.php";
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
    };

    $.ajax({
      url: "../controllers/usuario_controller.php",
      type: "POST",
      data: { accion: "editar", ...datos },
      dataType: "json",
      success: function (respuesta) {
        if (respuesta.status === "success") {
          Swal.fire({
            icon: "success",
            title: "Usuario editado",
            text: "¡Usuario editado exitosamente!",
            showConfirmButton: false,
            timer: 1200,
          }).then(() => {
            window.location.href = "gestionar_usuarios.php";
          });
        } else if (respuesta.status === "duplicado") {
          Swal.fire({
            icon: "warning",
            title: "Correo ya registrado",
            text: "Este correo ya pertenece a otro usuario.",
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

//* eliminar usuario
$(document).ready(function () {
  $(".btnEliminarUsuario").on("click", function () {
    let id = $(this).data("id");

    Swal.fire({
      title: "¿Eliminar usuario?",
      text: "Esta acción no se puede deshacer.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sí",
      cancelButtonText: "No",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "../controllers/usuario_controller.php",
          type: "POST",
          data: { accion: "eliminar", id: id },
          dataType: "json",
          success: function (respuesta) {
            if (respuesta.status === "success") {
              Swal.fire({
                icon: "success",
                title: "Usuario eliminado",
                showConfirmButton: false,
                timer: 1200,
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
          Swal.fire({
            icon: "success",
            title: "Préstamo creado",
            text: "¡Préstamo creado exitosamente!",
            showConfirmButton: false,
            timer: 1200,
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
          Swal.fire({
            icon: "success",
            title: "Reserva aprobada",
            text: "La reserva fue aprobada correctamente.",
            showConfirmButton: false,
            timer: 1200,
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
          Swal.fire({
            icon: "success",
            title: "Reserva rechazada",
            text: "La reserva fue rechazada correctamente.",
            showConfirmButton: false,
            timer: 1200,
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
          Swal.fire({
            icon: "success",
            title: "Perfil actualizado",
            text: "¡Perfil actualizado exitosamente!",
            showConfirmButton: false,
            timer: 1200,
          }).then(() => {
            window.location.href = "dashboard.php";
          });
        } else if (respuesta.status === "duplicado") {
          Swal.fire({
            icon: "warning",
            title: "Correo ya registrado",
            text: "Este correo ya pertenece a otro usuario.",
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

//* generar informes PDF y Excel
$(document).ready(function () {
  //* validación en tiempo real de las fechas
  //? el evento change es para que se sepa si uno de los dos campos de fechas cambio para verificar las y saber si son validas
  $("#fecha_inicio, #fecha_fin").change(function () {
    let fechaInicio = $("#fecha_inicio").val();
    let fechaFin = $("#fecha_fin").val();

    if (fechaInicio && fechaFin && fechaInicio > fechaFin) {
      Swal.fire({
        icon: "error",
        title: "Rango de fechas inválido",
        text: "La fecha de inicio no puede ser posterior a la fecha fin.",
      });
      //* limpiar el campo otros porque sino hay errores
      $(this).val("");
    }
  });

  //* generar informe PDF
  $("#btnPDF").on("click", function () {
    let tipo = $("#tipo_informe").val();
    let fechaInicio = $("#fecha_inicio").val();
    let fechaFin = $("#fecha_fin").val();

    //* validar fechas para informes que las requieren
    if (
      (tipo === "prestamos" || tipo === "reservas") &&
      (!fechaInicio || !fechaFin)
    ) {
      Swal.fire({
        icon: "warning",
        title: "Fechas requeridas",
        text: "Por favor seleccione un rango de fechas para este tipo de informe.",
      });
      return;
    }

    //* validar que fecha inicio no sea posterior a fecha fin
    if (fechaInicio && fechaFin && fechaInicio > fechaFin) {
      Swal.fire({
        icon: "error",
        title: "Rango de fechas inválido",
        text: "La fecha de inicio no puede ser posterior a la fecha fin.",
      });
      return;
    }

    //* hacer URL
    let url = `../controllers/informes_controller.php?tipo=${tipo}`;
    if (fechaInicio && fechaFin) {
      url += `&fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
    }

    window.open(url, "_blank");
  });

  //* generar informe Excel
  $("#btnExcel").click(function () {
    let tipo = $("#tipo_informe").val();
    let fechaInicio = $("#fecha_inicio").val();
    let fechaFin = $("#fecha_fin").val();

    //* validar fechas para informes que las requieren
    if (
      (tipo === "prestamos" || tipo === "reservas") &&
      (!fechaInicio || !fechaFin)
    ) {
      Swal.fire({
        icon: "warning",
        title: "Fechas requeridas",
        text: "Por favor seleccione un rango de fechas para este tipo de informe.",
      });
      return;
    }

    //* validar que fecha inicio no sea posterior a fecha fin
    if (fechaInicio && fechaFin && fechaInicio > fechaFin) {
      Swal.fire({
        icon: "error",
        title: "Rango de fechas inválido",
        text: "La fecha de inicio no puede ser posterior a la fecha fin.",
      });
      return;
    }

    //* construir URL con parámetros
    let url = `../controllers/informes_excel_controller.php?tipo=${tipo}`;
    if (fechaInicio && fechaFin) {
      url += `&fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
    }

    //* mostrar mensaje de descarga
    Swal.fire({
      icon: "success",
      title: "Generando Excel",
      text: "Su archivo se descargará en breve...",
      timer: 2000,
      showConfirmButton: false,
    });

    //* descargar archivo Excel
    window.location.href = url;
  });

  //* mostrar y ocultar campos de fecha según el tipo de informe
  $("#tipo_informe").change(function () {
    let tipo = $(this).val();
    let fechasContainer = $(".row.mb-3");

    if (tipo === "prestamos" || tipo === "reservas") {
      fechasContainer.show();
      $("#fecha_inicio").attr("required", true);
      $("#fecha_fin").attr("required", true);
    } else {
      fechasContainer.hide();
      $("#fecha_inicio").removeAttr("required").val("");
      $("#fecha_fin").removeAttr("required").val("");
    }
  });

  //* Inicializar visibilidad de fechas
  $("#tipo_informe").trigger("change");
});