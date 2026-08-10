<?php
$titulo = 'SideGeek | Carrito';
$cssAdicional = 'carrito.css';
require __DIR__ . '/../layouts/header.php';

$carrito = $data['carrito'] ?? [];
$subtotal = $data['subtotal'] ?? 0;
$total = $data['total'] ?? 0;
$mensaje = $data['mensaje'] ?? null;
$tipoMensaje = $data['tipoMensaje'] ?? null;
?>

<main class="seccion">
    <div class="encabezado-seccion">
        <div>
            <p class="etiqueta">Compra</p>
            <h1>Tu carrito</h1>
        </div>
    </div>

    <?php if ($mensaje): ?>
        <div class="alerta <?= $tipoMensaje === 'error' ? 'alerta-error' : 'alerta-exito' ?>">
            <?= htmlspecialchars($mensaje) ?>
        </div>
    <?php endif; ?>

    <section class="carrito-layout">
        <div class="lista-carrito">
            <?php if (empty($carrito)): ?>
                <div class="vacio">
                    <p>Tu carrito está vacío.</p>
                    <a class="boton" href="<?= $base ?>/productos">Ver catálogo</a>
                </div>
            <?php else: ?>
                <?php foreach ($carrito as $item): ?>
                    <?php require __DIR__ . '/_item.php'; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php require __DIR__ . '/_resumen.php'; ?>
    </section>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
<script src="<?= $base ?>/js/carrito.js"></script>
