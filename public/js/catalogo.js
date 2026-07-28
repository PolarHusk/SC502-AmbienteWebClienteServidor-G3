document.addEventListener('DOMContentLoaded', () => {
    const busqueda = document.querySelector('#busqueda');
    const productos = document.querySelectorAll('.producto-card');

    if (!busqueda) return;

    busqueda.addEventListener('input', () => {
        const texto = busqueda.value.toLowerCase();

        productos.forEach(producto => {
            const nombre = producto
                .querySelector('h3')
                .textContent
                .toLowerCase();

            producto.style.display =
                nombre.includes(texto) ? '' : 'none';
        });
    });
});