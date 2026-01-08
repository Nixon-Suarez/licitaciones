const formulario_ajax = document.querySelectorAll(".FormularioAjax");

formulario_ajax.forEach(formulario => {
    formulario.addEventListener('submit', function (e) {
        e.preventDefault();

        enviarFormulario({
            action: this.getAttribute("action"),
            method: this.getAttribute("method"),
            data: new FormData(this)
        });
    });
});


function enviarFormulario({ action, method = "POST", data }) {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "Se enviará toda la información",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar"
    }).then(result => {
        if (!result.isConfirmed) return;

        axios({
            method,
            url: action,
            data
        })
        .then(response => {
            alertas_ajax(response.data);
        })
        .catch(error => {
            console.error(error);
            Swal.fire(
                "Error",
                "Ocurrió un error en el servidor",
                "error"
            );
        });
    });
}


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