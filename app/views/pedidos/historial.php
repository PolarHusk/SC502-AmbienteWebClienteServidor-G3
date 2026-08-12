<?php

$titulo = 'SideGeek | Mis pedidos';

require __DIR__ . '/../layouts/header.php';

?>


<main class="seccion">


    <div class="encabezado-seccion">

        <p class="etiqueta">
            MI CUENTA
        </p>

        <h1>
            Historial de pedidos
        </h1>

    </div>



    <?php if (empty($pedidos)): ?>


        <div class="vacio">

            Todavía no has realizado pedidos.

        </div>


    <?php else: ?>


        <div class="resumen">


            <?php foreach ($pedidos as $pedido): ?>


                <div class="pedido-item">


                    <h3>

                        <?= htmlspecialchars(
                            $pedido['numero_pedido']
                        ) ?>

                    </h3>



                    <p>

                        <strong>Fecha:</strong>

                        <?= htmlspecialchars(
                            $pedido['creado_en']
                        ) ?>

                    </p>



                    <p>

                        <strong>Estado:</strong>

                        <?= htmlspecialchars(
                            ucfirst(
                                $pedido['estado']
                            )
                        ) ?>

                    </p>



                    <p>

                        <strong>Total:</strong>

                        ₡<?= number_format(
                            $pedido['total'],
                            0,
                            ',',
                            '.'
                        ) ?>

                    </p>



                    <?php if (
                        !empty($pedido['numero_factura'])
                    ): ?>


                        <p>

                            <strong>
                                Factura:
                            </strong>

                            <?= htmlspecialchars(
                                $pedido['numero_factura']
                            ) ?>

                        </p>


                    <?php endif; ?>



                    <?php if (
                        !empty($pedido['factura_id'])
                    ): ?>


                        <a
                            class="boton"

                            href="<?= $base ?>/pedidos/verFactura/<?= (int) $pedido['factura_id'] ?>"
                        >

                            Ver factura

                        </a>


                    <?php endif; ?>


                </div>


                <hr>


            <?php endforeach; ?>


        </div>


    <?php endif; ?>


</main>


<?php

require __DIR__ . '/../layouts/footer.php';

?>