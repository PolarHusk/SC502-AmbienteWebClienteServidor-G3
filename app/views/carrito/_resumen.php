<aside class="resumen">
    <h2>Resumen</h2>

    <div class="fila-resumen">
        <span>Subtotal</span>
        <strong>₡<?= number_format($subtotal ?? 0, 0, ',', '.') ?></strong>
    </div>

    <div class="fila-resumen">
        <span>Envío</span>
        <strong>Gratis</strong>
    </div>

    <hr>

    <div class="fila-resumen total">
        <span>Total</span>
        <strong>₡<?= number_format($total ?? 0, 0, ',', '.') ?></strong>
    </div>

    <?php if (!empty($carrito)): ?>
        <a class="boton boton-ancho" href="<?= $base ?>/pedidos/checkout">
            Finalizar compra
        </a>
    <?php endif; ?>
</aside>
