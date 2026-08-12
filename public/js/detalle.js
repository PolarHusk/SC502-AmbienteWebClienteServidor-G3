document.addEventListener('DOMContentLoaded', () => {
    const pagina = document.querySelector('#pagina-detalle-producto');

    if (!pagina) {
        return;
    }

    const apiBase = pagina.dataset.apiBase;
    const productoId = pagina.dataset.productoId;
    const contenedor = document.querySelector(
        '#contenedor-detalle-producto'
    );
    const mensaje = document.querySelector('#mensaje-detalle');

    const formatoMoneda = new Intl.NumberFormat('es-CR', {
        style: 'currency',
        currency: 'CRC',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });

    function mostrarMensaje(texto = '') {
        mensaje.textContent = texto;
    }

    function obtenerImagenes(producto) {
        const imagenes = [];

        const imagenPrincipal =
            producto.imagen_catalogo ||
            producto.imagen_principal_url;

        if (imagenPrincipal) {
            imagenes.push(imagenPrincipal);
        }

        if (Array.isArray(producto.imagenes)) {
            producto.imagenes.forEach(imagen => {
                if (imagen.url_imagen) {
                    imagenes.push(imagen.url_imagen);
                }
            });
        }

        return [...new Set(imagenes)];
    }

    function crearGaleria(producto) {
        const galeria = document.createElement('div');

        const imagenPrincipal = document.createElement('div');
        imagenPrincipal.className = 'detalle-imagen';

        const imagenes = obtenerImagenes(producto);

        if (imagenes.length === 0) {
            imagenPrincipal.textContent = '🎁';
            return galeria;
        }

        const imagen = document.createElement('img');
        imagen.src = imagenes[0];
        imagen.alt = producto.nombre;

        imagenPrincipal.appendChild(imagen);
        galeria.appendChild(imagenPrincipal);

        if (imagenes.length > 1) {
            const miniaturas = document.createElement('div');
            miniaturas.className = 'galeria-miniaturas';

            imagenes.forEach((url, indice) => {
                const boton = document.createElement('button');
                boton.type = 'button';
                boton.className = 'miniatura';

                if (indice === 0) {
                    boton.classList.add('miniatura-activa');
                }

                const miniatura = document.createElement('img');
                miniatura.src = url;
                miniatura.alt = `Imagen ${indice + 1} de ${producto.nombre}`;

                boton.appendChild(miniatura);

                boton.addEventListener('click', () => {
                    imagen.src = url;

                    miniaturas
                        .querySelectorAll('.miniatura')
                        .forEach(item => {
                            item.classList.remove('miniatura-activa');
                        });

                    boton.classList.add('miniatura-activa');
                });

                miniaturas.appendChild(boton);
            });

            galeria.appendChild(miniaturas);
        }

        return galeria;
    }

    function crearFormularioCarrito(producto) {
        const formulario = document.createElement('form');
        formulario.method = 'POST';
        formulario.action = `${apiBase.replace('/producto', '')}/carrito/agregar`;

        const productoIdInput = document.createElement('input');
        productoIdInput.type = 'hidden';
        productoIdInput.name = 'producto_id';
        productoIdInput.value = producto.id;

        const etiquetaCantidad = document.createElement('label');
        etiquetaCantidad.htmlFor = 'cantidad-producto';
        etiquetaCantidad.textContent = 'Cantidad';

        const cantidad = document.createElement('input');
        cantidad.className = 'cantidad';
        cantidad.id = 'cantidad-producto';
        cantidad.type = 'number';
        cantidad.name = 'cantidad';
        cantidad.min = '1';
        cantidad.max = producto.stock;
        cantidad.value = '1';

        const boton = document.createElement('button');
        boton.className = 'boton';
        boton.type = 'submit';
        boton.textContent = 'Agregar al carrito';

        formulario.append(
            productoIdInput,
            etiquetaCantidad,
            cantidad,
            boton
        );

        return formulario;
    }

    function mostrarProducto(producto) {
        contenedor.replaceChildren();

        const galeria = crearGaleria(producto);

        const informacion = document.createElement('div');
        informacion.className = 'detalle-contenido';

        const categoria = document.createElement('p');
        categoria.className = 'etiqueta';
        categoria.textContent = producto.categoria;

        const nombre = document.createElement('h1');
        nombre.textContent = producto.nombre;

        const descripcion = document.createElement('p');
        descripcion.textContent = producto.descripcion;

        const stock = document.createElement('p');
        stock.innerHTML = `<strong>Disponibles:</strong> ${producto.stock}`;

        const precio = document.createElement('p');
        precio.className = 'precio';
        precio.textContent = formatoMoneda.format(producto.precio);

        informacion.append(categoria, nombre, descripcion, stock);

        if (Number(producto.descuento_porcentaje) > 0) {
            const descuento = document.createElement('p');
            descuento.textContent =
                `Descuento: ${producto.descuento_porcentaje}%`;

            informacion.appendChild(descuento);
        }

        informacion.appendChild(precio);

        if (Number(producto.stock) > 0) {
            informacion.appendChild(crearFormularioCarrito(producto));
        } else {
            const agotado = document.createElement('p');
            agotado.className = 'mensaje error';
            agotado.textContent = 'Producto agotado.';

            informacion.appendChild(agotado);
        }

        contenedor.append(galeria, informacion);
        mostrarMensaje('');
    }

    async function cargarProducto() {
        try {
            const respuesta = await fetch(
                `${apiBase}/apiProducto/${productoId}`
            );

            const resultado = await respuesta.json();

            if (!respuesta.ok || !resultado.success) {
                throw new Error(
                    resultado.message || 'Producto no encontrado.'
                );
            }

            mostrarProducto(resultado.data);
        } catch (error) {
            contenedor.replaceChildren();
            mostrarMensaje(error.message);
        }
    }

    cargarProducto();
});