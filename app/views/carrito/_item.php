<article class="item-carrito">
    <div class="item-icono">
        <?php if (!empty($item['imagen_principal_url'])): ?>
            <img
                src="<?= htmlspecialchars($item['imagen_principal_url']) ?>"
                alt="<?= htmlspecialchars($item['nombre']) ?>"
            >
        <?php else: ?>
            
        <?php endif; ?>
    </div>

    <div class="item-info">
        <p class="etiqueta"><?= htmlspecialchars($item['categoria']) ?></p>
        <h3><?= htmlspecialchars($item['nombre']) ?></h3>
        <p>₡<?= number_format($item['precio'], 0, ',', '.') ?> c/u</p>
        <small>Stock disponible: <?= (int) $item['stock'] ?></small>
    </div>

    <div class="item-acciones">
        <form method="POST" action="<?= $base ?>/carrito/actualizar" class="form-cantidad">
            <input type="hidden" name="producto_id" value="<?= (int) $item['producto_id'] ?>">
            <input
                type="number"
                name="cantidad"
                min="1"
                max="<?= (int) $item['stock'] ?>"
                value="<?= (int) $item['cantidad'] ?>"
                aria-label="Cantidad de <?= htmlspecialchars($item['nombre']) ?>"
            >
            <button class="boton" type="submit">Actualizar</button>
        </form>

        <p class="subtotal-item">
            ₡<?= number_format($item['subtotal'], 0, ',', '.') ?>
        </p>

        <form method="POST" action="<?= $base ?>/carrito/eliminar" class="form-eliminar">
            <input type="hidden" name="producto_id" value="<?= (int) $item['producto_id'] ?>">
            <button class="boton boton-peligro" type="submit">Eliminar</button>
        </form>
    </div>
</article>
