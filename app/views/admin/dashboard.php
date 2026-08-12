<?php
$titulo = 'SideGeek | Administración';
$base = '/SC502-AmbienteWebClienteServidor-G3/public';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?></title>

    <link rel="stylesheet" href="<?= $base ?>/css/style.css">
    <link rel="stylesheet" href="<?= $base ?>/css/admin.css">
</head>
<body class="admin-body">

<aside class="sidebar">
    <a class="logo" href="<?= $base ?>/">
        Side<span>Geek</span>
    </a>

    <nav>
        <a href="#resumen">Resumen</a>
        <a href="#productos">Productos</a>
        <a href="#inventario">Inventario</a>
        <a href="#pedidos">Pedidos</a>
        <a href="<?= $base ?>/auth/logout">Cerrar sesión</a>    </nav>
</aside>

<main class="admin-main">
    <section id="resumen">
        <p class="etiqueta">Panel administrativo</p>
        <h1>Resumen general</h1>

        <div class="tarjetas-admin">
            <article>
                <span>Productos</span>
                <strong><?= $totalProductos ?? 0 ?></strong>
            </article>

            <article>
                <span>Stock total</span>
                <strong><?= $stockTotal ?? 0 ?></strong>
            </article>

            <article>
                <span>Pedidos</span>
                <strong><?= $totalPedidos ?? 0 ?></strong>
            </article>
        </div>
    </section>

    <section id="productos" class="panel-admin">
        <h2>Gestión de productos</h2>

        <a class="boton" href="<?= $base ?>/admin/productos/crear">
            Agregar producto
        </a>

        <div class="tabla-contenedor">
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?= htmlspecialchars($producto['nombre']) ?></td>
                            <td><?= htmlspecialchars($producto['categoria']) ?></td>
                            <td>
                                ₡<?= number_format($producto['precio'], 0, ',', '.') ?>
                            </td>
                            <td><?= $producto['stock'] ?></td>
                            <td>
                                <a
                                    class="boton boton-secundario"
                                    href="<?= $base ?>/admin/productos/editar?id=<?= $producto['id'] ?>"
                                >
                                    Editar
                                </a>

                                <form
                                    method="POST"
                                    action="<?= $base ?>/admin/productos/eliminar"
                                    style="display:inline"
                                >
                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= $producto['id'] ?>"
                                    >

                                    <button class="boton boton-peligro" type="submit">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section id="inventario" class="panel-admin">
        <h2>Inventario bajo</h2>

        <?php foreach ($stockBajo ?? [] as $producto): ?>
            <p class="stock-bajo">
                <?= htmlspecialchars($producto['nombre']) ?>:
                <?= $producto['stock'] ?> unidad(es)
            </p>
        <?php endforeach; ?>
    </section>

    <section id="pedidos" class="panel-admin">
        <h2>Pedidos recientes</h2>

        <div class="tabla-contenedor">
            <table>
                <thead>
                    <tr>
                        <th>Pedido</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($pedidos ?? [] as $pedido): ?>
                        <tr>
                            <td><?= htmlspecialchars($pedido['numero_pedido']) ?></td>
                            <td><?= htmlspecialchars($pedido['cliente']) ?></td>
                            <td>
                                ₡<?= number_format($pedido['total'], 0, ',', '.') ?>
                            </td>
                            <td><?= htmlspecialchars($pedido['estado']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<script src="<?= $base ?>/js/admin.js"></script>
</body>
</html>