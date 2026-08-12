<?php
$titulo = 'SideGeek | Detalle de producto';
require __DIR__ . '/../layouts/header.php';
?>

<main
    class="seccion"
    id="pagina-detalle-producto"
    data-api-base="<?= htmlspecialchars($base) ?>/producto"
    data-producto-id="<?= (int) $productoId ?>"
>
    <a class="enlace" href="<?= $base ?>/producto/catalogo">
        ← Volver al catálogo
    </a>

    <p class="mensaje" id="mensaje-detalle" aria-live="polite">
        Cargando producto...
    </p>

    <section
        class="detalle-producto"
        id="contenedor-detalle-producto"
    ></section>
</main>

<script src="<?= $base ?>/js/detalle.js"></script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>