// boton para cerrar session
let btn_exit=document.getElementById("btn_exit");
btn_exit.addEventListener("click", function(evento){
    evento.preventDefault(); // previene el evento por defecto del boton
    Swal.fire({
        title: "Quieres cerrar session?",
        text: "La sesion se cerrara",
        icon: "question",
        theme: "light",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            let url = this.getAttribute("href"); // obtiene la url del boton
            window.location.href = url; // redirecciona a la url del boton
        }
    });
});

document.addEventListener('DOMContentLoaded', () => {

  const boton = document.getElementById('btnToggle');
  const sidebar = document.querySelector('#sidebar');
  const content = document.querySelector('.content');

  if (boton && sidebar && content) {
      boton.addEventListener('click', () => {
          sidebar.classList.toggle('sidebar-collapsed');
          content.classList.toggle('content-collapsed');
      });
  }
});

document.getElementById('formFechas').addEventListener('submit', function (e) {
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaCierre = document.getElementById('fecha_cierre').value;
    const horaInicio = document.getElementById('hora_inicio').value;
    const horaCierre = document.getElementById('hora_cierre').value;

    let valido = true;

    // Fecha cierre no menor
    if (fechaCierre < fechaInicio) {
        valido = false;
        document.getElementById('fecha_cierre').classList.add('is-invalid');
    }

    // Si fechas iguales → validar horas
    if (fechaInicio === fechaCierre && horaCierre <= horaInicio) {
        valido = false;
        document.getElementById('hora_cierre').classList.add('is-invalid');
    }

    if (!this.checkValidity() || !valido) {
        e.preventDefault();
        e.stopPropagation();
    }

    this.classList.add('was-validated');
});

