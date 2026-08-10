document.addEventListener('DOMContentLoaded', () => {
    const formulariosEliminar = document.querySelectorAll('.form-eliminar');

    formulariosEliminar.forEach(formulario => {
        formulario.addEventListener('submit', evento => {
            if (!confirm('¿Deseas eliminar este producto del carrito?')) {
                evento.preventDefault();
            }
        });
    });

    const cantidades = document.querySelectorAll('.form-cantidad input[name="cantidad"]');

    cantidades.forEach(input => {
        input.addEventListener('input', () => {
            const minimo = Number(input.min || 1);
            const maximo = Number(input.max || 0);
            let valor = Number(input.value || minimo);

            if (valor < minimo) {
                input.value = minimo;
            }

            if (maximo > 0 && valor > maximo) {
                input.value = maximo;
            }
        });
    });
});
