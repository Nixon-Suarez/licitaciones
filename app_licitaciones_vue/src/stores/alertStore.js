import Swal from 'sweetalert2';

function alertas_ajax(alerta){
  if(alerta.tipo=="simple"){
      Swal.fire({
          icon: alerta.icono,
          title: alerta.titulo,
          text: alerta.texto,
          theme: "light",
          confirmButtonText: "Aceptar"
      });
  }else if(alerta.tipo=="recargar"){
      Swal.fire({
          icon: alerta.icono,
          title: alerta.titulo,
          text: alerta.texto,
          theme: "light",
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Aceptar",
          cancelButtonText: "Cancelar"
      }).then((result) => {
          if(result.isConfirmed) {
              location.reload(); // recarga la pagina
          }
      });
  }else if(alerta.tipo=="limpiar"){
      Swal.fire({
          icon: alerta.icono,
          title: alerta.titulo,
          text: alerta.texto,
          theme: "light",
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Aceptar",
          cancelButtonText: "Cancelar"
      }).then((result) => {
          if(result.isConfirmed) {
              let formulario = document.querySelector(".FormularioAjax"); //selecciona el primer formulario con la clase FormularioAjax
              formulario.reset(); //limpia el formulario
          }
      });
  }else if(alerta.tipo=="redireccionar"){
      Swal.fire({
          icon: alerta.icono,
          title: alerta.titulo,
          text: alerta.texto,
          theme: "light",
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Aceptar",
          cancelButtonText: "Cancelar"
      }).then((result) => {
          if(result.isConfirmed) {
              window.location.href = alerta.url; // redirecciona a la url que se le pasa
          }
      });
  }
}
