<?php
$titulo = 'SideGeek | Carrito';
require __DIR__ . '/../layouts/header.php';
?>

<main class="seccion">
    <div class="encabezado-seccion">
        <p class="etiqueta">Compra</p>
        <h1>Tu carrito</h1>
    </div>

    <section class="carrito-layout">
        <div>
            <?php if (empty($carrito)): ?>
                <div class="vacio">
                    Tu carrito está vacío.
                </div>
            <?php else: ?>
                <?php foreach ($carrito as $item): ?>
                    <article class="item-carrito">
                        <div class="item-icono">🎁</div>

                        <div>
                            <p class="etiqueta">
                                <?= htmlspecialchars($item['categoria']) ?>
                            </p>

                            <h3>
                                <?= htmlspecialchars($item['nombre']) ?>
                            </h3>

                            <p>
                                ₡<?= number_format($item['precio'], 0, ',', '.') ?>
                                c/u
                            </p>
                        </div>

                        <div class="item-acciones">
                            <form method="POST" action="<?= $base ?>/carrito/actualizar">
                                <input
                                    type="hidden"
                                    name="producto_id"
                                    value="<?= $item['producto_id'] ?>"
                                >

                                <input
                                    type="number"
                                    name="cantidad"
                                    min="1"
                                    value="<?= $item['cantidad'] ?>"
                                >

                                <button class="boton" type="submit">
                                    Actualizar
                                </button>
                            </form>

                            <form method="POST" action="<?= $base ?>/carrito/eliminar">
                                <input
                                    type="hidden"
                                    name="producto_id"
                                    value="<?= $item['producto_id'] ?>"
                                >

                                <button class="boton boton-peligro" type="submit">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <aside class="resumen">
            <h2>Resumen</h2>

            <div class="fila-resumen">
                <span>Subtotal</span>
                <strong>
                    ₡<?= number_format($subtotal ?? 0, 0, ',', '.') ?>
                </strong>
            </div>

            <div class="fila-resumen">
                <span>Envío</span>
                <strong>Gratis</strong>
            </div>

            <hr>

            <div class="fila-resumen total">
                <span>Total</span>
                <strong>
                    ₡<?= number_format($total ?? 0, 0, ',', '.') ?>
                </strong>
            </div>

            <?php if (!empty($carrito)): ?>
                <a class="boton boton-ancho" href="<?= $base ?>/pedidos/checkout">
                    Finalizar compra
                </a>
            <?php endif; ?>
        </aside>
    </section>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
<script src="<?= $base ?>/js/carrito.js"></script>