<?php
$titulo = 'SideGeek | Catálogo';
require __DIR__ . '/../layouts/header.php';
?>

<main class="seccion">
    <div class="encabezado-seccion">
        <div>
            <p class="etiqueta">Catálogo</p>
            <h1>Todos nuestros productos</h1>
        </div>
    </div>

    <form class="filtros" method="GET" action="<?= $base ?>/productos">
        <input
            type="search"
            name="buscar"
            placeholder="Buscar producto..."
            value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>"
        >

        <select name="categoria">
            <option value="">Todas las categorías</option>

            <?php foreach ($categorias as $categoria): ?>
                <option
                    value="<?= htmlspecialchars($categoria['slug']) ?>"
                    <?= ($_GET['categoria'] ?? '') === $categoria['slug']
                        ? 'selected'
                        : '' ?>
                >
                    <?= htmlspecialchars($categoria['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="orden">
            <option value="">Ordenar por</option>
            <option value="menor">Precio menor</option>
            <option value="mayor">Precio mayor</option>
            <option value="nombre">Nombre</option>
        </select>

        <button class="boton" type="submit">
            Filtrar
        </button>
    </form>

    <?php if (empty($productos)): ?>
        <p class="mensaje">
            No se encontraron productos.
        </p>
    <?php endif; ?>

    <section class="grid-productos">
        <?php foreach ($productos as $producto): ?>
            <?php require __DIR__ . '/_tarjeta.php'; ?>
        <?php endforeach; ?>
    </section>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
<script src="<?= $base ?>/js/catalogo.js"></script>