<?php
$titulo = 'SideGeek | Catálogo';
require __DIR__ . '/../layouts/header.php';
?>

<main
    class="seccion"
    id="catalogo"
    data-api-base="<?= htmlspecialchars($base) ?>/producto"
>
    <div class="encabezado-seccion">
        <div>
            <p class="etiqueta">Catálogo</p>
            <h1>Todos nuestros productos</h1>
        </div>
    </div>

    <form class="filtros" id="filtros-catalogo">
        <input
            id="buscar-producto"
            type="search"
            placeholder="Buscar producto..."
            autocomplete="off"
        >

        <select id="filtro-categoria">
            <option value="">Cargando categorías...</option>
        </select>

        <select id="filtro-orden">
            <option value="">Ordenar por</option>
            <option value="menor">Precio menor</option>
            <option value="mayor">Precio mayor</option>
            <option value="nombre">Nombre</option>
        </select>

        <button class="boton" type="submit">
            Filtrar
        </button>
    </form>

    <p class="mensaje" id="mensaje-catalogo" aria-live="polite">
        Cargando productos...
    </p>

    <section
        class="grid-productos"
        id="grid-productos"
        aria-live="polite"
    ></section>
</main>

<script src="<?= $base ?>/js/catalogo.js"></script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>