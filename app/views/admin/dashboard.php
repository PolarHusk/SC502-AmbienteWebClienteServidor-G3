<?php
$titulo = 'SideGeek | Administración';
$base = '/SC502-AmbienteWebClienteServidor-G3-main/public';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $titulo ?></title>

    <link rel="stylesheet" href="<?= $base ?>/css/style.css">
    <link rel="stylesheet" href="<?= $base ?>/css/admin.css">
</head>

<body class="admin-body">

<header class="admin-header">

    <a class="logo" href="<?= $base ?>/admin/index">
        Side<span>Geek</span>
    </a>

    <div class="admin-header-derecha">

        <span>
            Hola, <?= htmlspecialchars($_SESSION['usuario']['nombre'] ?? 'Admin') ?>
        </span>

        <a href="<?= $base ?>/auth/logout">
            Cerrar sesión
        </a>

    </div>

</header>


<main class="admin-main">


    <section id="resumen">

        <p class="etiqueta">
            Panel administrativo
        </p>

        <h1>
            Resumen general
        </h1>


        <div class="tarjetas-admin">

            <article>
                <span>Productos</span>

                <strong>
                    <?= $totalProductos ?? 0 ?>
                </strong>
            </article>


            <article>
                <span>Stock total</span>

                <strong>
                    <?= $stockTotal ?? 0 ?>
                </strong>
            </article>


            <article>
                <span>Pedidos</span>

                <strong>
                    <?= $totalPedidos ?? 0 ?>
                </strong>
            </article>

        </div>

    </section>


    <section id="productos" class="panel-admin">

        <h2>
            Gestión de productos
        </h2>


        <a
            class="boton"
            href="<?= $base ?>/admin/crear"
        >
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

                    <?php foreach ($productos ?? [] as $producto): ?>

                        <tr>

                            <td>
                                <?= htmlspecialchars($producto['nombre']) ?>
                            </td>


                            <td>
                                <?= htmlspecialchars($producto['categoria']) ?>
                            </td>


                            <td>
                                ₡<?= number_format(
                                    $producto['precio'],
                                    0,
                                    ',',
                                    '.'
                                ) ?>
                            </td>


                            <td>
                                <?= (int) $producto['stock'] ?>
                            </td>


                            <td>

                               <a
                                    class="boton boton-secundario"
                                    href="<?= $base ?>/admin/editar?id=<?= (int) $producto['id'] ?>"
                                >
                                    Editar
                                </a>

                              <form
                                    method="POST"
                                    action="<?= $base ?>/admin/eliminar"
                                    style="display:inline"
                                >

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= (int) $producto['id'] ?>"
                                    >

                                    <button
                                        class="boton boton-peligro"
                                        type="submit"
                                    >
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

        <h2>
            Inventario bajo
        </h2>


        <?php if (empty($inventarioBajo)): ?>

            <p>
                No hay productos con stock bajo.
            </p>

        <?php else: ?>


            <?php foreach ($inventarioBajo as $producto): ?>

                <p class="stock-bajo">

                     <?= htmlspecialchars($producto['nombre']) ?>:

                    <strong>
                        <?= (int) $producto['stock'] ?> unidad(es)
                    </strong>

                </p>

            <?php endforeach; ?>


        <?php endif; ?>

    </section>




    <section id="pedidos" class="panel-admin">

        <h2>
            Pedidos recientes
        </h2>


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

                    <?php if (empty($pedidos)): ?>

                        <tr>

                            <td colspan="4">
                                No hay pedidos registrados.
                            </td>

                        </tr>

                    <?php else: ?>


                        <?php foreach ($pedidos as $pedido): ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars(
                                        $pedido['numero_pedido']
                                    ) ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars(
                                        $pedido['cliente']
                                    ) ?>
                                </td>


                                <td>
                                    ₡<?= number_format(
                                        $pedido['total'],
                                        0,
                                        ',',
                                        '.'
                                    ) ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars(
                                        ucfirst($pedido['estado'])
                                    ) ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>


                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </section>

</main>


<script src="<?= $base ?>/js/admin.js"></script>

</body>

</html>