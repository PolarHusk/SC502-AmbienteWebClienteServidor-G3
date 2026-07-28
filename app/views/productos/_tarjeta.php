<article class="producto-card">
    <a href="<?= $base ?>/producto?id=<?= $producto['id'] ?>">
        <div class="producto-imagen">
            <?php if (!empty($producto['imagen_principal_url'])): ?>
                <img
                    src="<?= htmlspecialchars($producto['imagen_principal_url']) ?>"
                    alt="<?= htmlspecialchars($producto['nombre']) ?>"
                >
            <?php else: ?>
                🎁
            <?php endif; ?>
        </div>
    </a>

    <div class="producto-info">
        <p class="etiqueta">
            <?= htmlspecialchars($producto['categoria'] ?? '') ?>
        </p>

        <h3><?= htmlspecialchars($producto['nombre']) ?></h3>

        <p>Stock: <?= $producto['stock'] ?></p>

        <div class="producto-pie">
            <span class="precio">
                ₡<?= number_format($producto['precio'], 0, ',', '.') ?>
            </span>

            <form method="POST" action="<?= $base ?>/carrito/agregar">
                <input
                    type="hidden"
                    name="producto_id"
                    value="<?= $producto['id'] ?>"
                >

                <input
                    type="hidden"
                    name="cantidad"
                    value="1"
                >

                <button class="boton" type="submit">
                    Agregar
                </button>
            </form>
        </div>
    </div>
</article>