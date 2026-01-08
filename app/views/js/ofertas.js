document.getElementById('btnGuardarTodo').addEventListener('click', function () {

    const formPresupuesto = document.getElementById('formPresupuesto');
    const formFechas = document.getElementById('formFechas');

    const tabPresupuesto = new bootstrap.Tab(
        document.querySelector('#presupuesto-tab')
    );
    const tabFechas = new bootstrap.Tab(
        document.querySelector('#fechas-tab')
    );

    // Validar presupuesto
    if (!formPresupuesto.checkValidity()) {
        formPresupuesto.classList.add('was-validated');
        tabPresupuesto.show();
        return;
    }

    // Validar fechas
    if (!formFechas.checkValidity()) {
        formFechas.classList.add('was-validated');
        tabFechas.show();
        return;
    }

    // Combinar formularios
    const data = new FormData(formPresupuesto);
    new FormData(formFechas).forEach((value, key) => {
        data.append(key, value);
    });

    // Enviar usando la función común
    enviarFormulario({
        action: formPresupuesto.getAttribute("action"),
        method: "POST",
        data
    });
});

$(document).ready(function () {
    $('#actividad').select2({
        placeholder: "Seleccione una actividad",
        allowClear: true,
        width: '100%'
    });
});

