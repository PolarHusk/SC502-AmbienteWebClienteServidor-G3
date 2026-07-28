<?php
$titulo = 'SideGeek | Producto';
require __DIR__ . '/../layouts/header.php';
?>

<main class="seccion">
    <a class="enlace" href="<?= $base ?>/productos">
        ← Volver al catálogo
    </a>

    <section class="detalle-producto">
        <div class="detalle-imagen">
            <?php if (!empty($producto['imagen_principal_url'])): ?>
                <img
                    src="<?= htmlspecialchars($producto['imagen_principal_url']) ?>"
                    alt="<?= htmlspecialchars($producto['nombre']) ?>"
                >
            <?php else: ?>
                🎁
            <?php endif; ?>
        </div>

        <div class="detalle-contenido">
            <p class="etiqueta">
                <?= htmlspecialchars($producto['categoria']) ?>
            </p>

            <h1><?= htmlspecialchars($producto['nombre']) ?></h1>

            <p>
                <?= htmlspecialchars($producto['descripcion']) ?>
            </p>

            <p>
                <strong>Disponibles:</strong>
                <?= $producto['stock'] ?>
            </p>

            <?php if ($producto['descuento_porcentaje'] > 0): ?>
                <p>
                    Descuento:
                    <?= $producto['descuento_porcentaje'] ?>%
                </p>
            <?php endif; ?>

            <p class="precio">
                ₡<?= number_format($producto['precio'], 0, ',', '.') ?>
            </p>

            <?php if ($producto['stock'] > 0): ?>
                <form method="POST" action="<?= $base ?>/carrito/agregar">
                    <input
                        type="hidden"
                        name="producto_id"
                        value="<?= $producto['id'] ?>"
                    >

                    <label for="cantidad">Cantidad</label>

                    <input
                        class="cantidad"
                        id="cantidad"
                        type="number"
                        name="cantidad"
                        min="1"
                        max="<?= $producto['stock'] ?>"
                        value="1"
                    >

                    <button class="boton" type="submit">
                        Agregar al carrito
                    </button>
                </form>
            <?php else: ?>
                <p class="mensaje error">
                    Producto agotado.
                </p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>