document.addEventListener('DOMContentLoaded', () => {
    const formularios = document.querySelectorAll(
        'form[action$="/admin/productos/eliminar"]'
    );

    formularios.forEach(formulario => {
        formulario.addEventListener('submit', evento => {
            if (!confirm('¿Eliminar este producto?')) {
                evento.preventDefault();
            }
        });
    });
});