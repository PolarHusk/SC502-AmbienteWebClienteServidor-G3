document.addEventListener('DOMContentLoaded', () => {
    const formularios = document.querySelectorAll(
        'form[action$="/carrito/eliminar"]'
    );

    formularios.forEach(formulario => {
        formulario.addEventListener('submit', evento => {
            if (!confirm('¿Deseas eliminar este producto?')) {
                evento.preventDefault();
            }
        });
    });
});