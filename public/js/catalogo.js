document.addEventListener('DOMContentLoaded', () => {
    const catalogo = document.querySelector('#catalogo');

    if (!catalogo) {
        return;
    }

    const apiBase = catalogo.dataset.apiBase;
    const formulario = document.querySelector('#filtros-catalogo');
    const inputBuscar = document.querySelector('#buscar-producto');
    const filtroCategoria = document.querySelector('#filtro-categoria');
    const filtroOrden = document.querySelector('#filtro-orden');
    const grid = document.querySelector('#grid-productos');
    const mensaje = document.querySelector('#mensaje-catalogo');

    let productosActuales = [];
    let temporizadorBusqueda;

    const formatoMoneda = new Intl.NumberFormat('es-CR', {
        style: 'currency',
        currency: 'CRC',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });

    function mostrarMensaje(texto = '') {
        mensaje.textContent = texto;
    }

    function crearCard(producto) {
        const card = document.createElement('article');
        card.className = 'producto-card';

        const enlaceImagen = document.createElement('a');
        enlaceImagen.href = `${apiBase}/detalle/${producto.id}`;

        const contenedorImagen = document.createElement('div');
        contenedorImagen.className = 'producto-imagen';

        const imagenUrl =
            producto.imagen_catalogo ||
            producto.imagen_principal_url;

        if (imagenUrl) {
            const imagen = document.createElement('img');
            imagen.src = imagenUrl;
            imagen.alt = producto.nombre;
            imagen.loading = 'lazy';

            contenedorImagen.appendChild(imagen);
        } else {
            contenedorImagen.textContent = '🎁';
        }

        enlaceImagen.appendChild(contenedorImagen);

        const informacion = document.createElement('div');
        informacion.className = 'producto-info';

        const categoria = document.createElement('p');
        categoria.className = 'etiqueta';
        categoria.textContent = producto.categoria;

        const nombre = document.createElement('h3');
        nombre.textContent = producto.nombre;

        const descripcion = document.createElement('p');
        descripcion.textContent = producto.descripcion;

        const stock = document.createElement('p');
        stock.textContent = `Stock: ${producto.stock}`;

        const pie = document.createElement('div');
        pie.className = 'producto-pie';

        const precio = document.createElement('span');
        precio.className = 'precio';
        precio.textContent = formatoMoneda.format(producto.precio);

        const enlaceDetalle = document.createElement('a');
        enlaceDetalle.className = 'boton';
        enlaceDetalle.href = `${apiBase}/detalle/${producto.id}`;
        enlaceDetalle.textContent = 'Ver detalle';

        pie.append(precio, enlaceDetalle);
        informacion.append(categoria, nombre, descripcion, stock, pie);
        card.append(enlaceImagen, informacion);

        return card;
    }

    function aplicarFiltrosLocales() {
        const textoBusqueda = inputBuscar.value.trim().toLowerCase();
        const orden = filtroOrden.value;

        let productosFiltrados = productosActuales.filter(producto => {
            const nombre = producto.nombre.toLowerCase();
            const descripcion = producto.descripcion.toLowerCase();

            return (
                nombre.includes(textoBusqueda) ||
                descripcion.includes(textoBusqueda)
            );
        });

        if (orden === 'menor') {
            productosFiltrados.sort(
                (a, b) => Number(a.precio) - Number(b.precio)
            );
        }

        if (orden === 'mayor') {
            productosFiltrados.sort(
                (a, b) => Number(b.precio) - Number(a.precio)
            );
        }

        if (orden === 'nombre') {
            productosFiltrados.sort(
                (a, b) => a.nombre.localeCompare(b.nombre, 'es')
            );
        }

        grid.replaceChildren();

        if (productosFiltrados.length === 0) {
            mostrarMensaje('No se encontraron productos.');
            return;
        }

        productosFiltrados.forEach(producto => {
            grid.appendChild(crearCard(producto));
        });

        mostrarMensaje(
            `${productosFiltrados.length} producto(s) encontrado(s).`
        );
    }

    async function cargarCategorias() {
        try {
            const respuesta = await fetch(`${apiBase}/apiCategorias`);

            if (!respuesta.ok) {
                throw new Error('No fue posible cargar las categorías.');
            }

            const resultado = await respuesta.json();

            filtroCategoria.replaceChildren();

            const opcionTodas = document.createElement('option');
            opcionTodas.value = '';
            opcionTodas.textContent = 'Todas las categorías';
            filtroCategoria.appendChild(opcionTodas);

            resultado.data.forEach(categoria => {
                const opcion = document.createElement('option');
                opcion.value = categoria.slug;
                opcion.textContent = categoria.nombre;

                filtroCategoria.appendChild(opcion);
            });
        } catch (error) {
            filtroCategoria.replaceChildren();

            const opcionError = document.createElement('option');
            opcionError.value = '';
            opcionError.textContent = 'No se pudieron cargar categorías';

            filtroCategoria.appendChild(opcionError);
        }
    }

    async function cargarProductos() {
        mostrarMensaje('Cargando productos...');
        grid.replaceChildren();

        const parametros = new URLSearchParams();

        if (filtroCategoria.value) {
            parametros.set('categoria', filtroCategoria.value);
        }

        const url = parametros.toString()
            ? `${apiBase}/apiProductos?${parametros.toString()}`
            : `${apiBase}/apiProductos`;

        try {
            const respuesta = await fetch(url);

            if (!respuesta.ok) {
                throw new Error('No fue posible cargar los productos.');
            }

            const resultado = await respuesta.json();

            if (!resultado.success) {
                throw new Error('La API no devolvió productos.');
            }

            productosActuales = resultado.data;
            aplicarFiltrosLocales();
        } catch (error) {
            productosActuales = [];
            grid.replaceChildren();
            mostrarMensaje(
                'Ocurrió un error al cargar los productos. Intenta de nuevo.'
            );
        }
    }

    formulario.addEventListener('submit', evento => {
        evento.preventDefault();
        cargarProductos();
    });

    filtroCategoria.addEventListener('change', cargarProductos);

    filtroOrden.addEventListener('change', aplicarFiltrosLocales);

    inputBuscar.addEventListener('input', () => {
        clearTimeout(temporizadorBusqueda);

        temporizadorBusqueda = setTimeout(() => {
            aplicarFiltrosLocales();
        }, 250);
    });

    cargarCategorias();
    cargarProductos();
});