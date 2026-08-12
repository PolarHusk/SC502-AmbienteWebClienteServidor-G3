<?php

$titulo = 'SideGeek | Factura';

require __DIR__ . '/../layouts/header.php';

?>


<main class="seccion">


    <div class="factura">


        <div class="encabezado-seccion">


            <p class="etiqueta">

                COMPRA REALIZADA

            </p>


            <h1>

                Factura

            </h1>


        </div>



        <div class="resumen">


            <h2>

                <?= htmlspecialchars(
                    $factura['numero_factura']
                ) ?>

            </h2>



            <p>

                <strong>
                    Pedido:
                </strong>

                <?= htmlspecialchars(
                    $factura['numero_pedido']
                ) ?>

            </p>



            <p>

                <strong>
                    Cliente:
                </strong>

                <?= htmlspecialchars(
                    $factura['cliente']
                ) ?>

            </p>



            <p>

                <strong>
                    Correo:
                </strong>

                <?= htmlspecialchars(
                    $factura['correo']
                ) ?>

            </p>



            <p>

                <strong>
                    Fecha:
                </strong>

                <?= htmlspecialchars(
                    $factura['fecha_emision']
                ) ?>

            </p>


        </div>



        <br>



        <div class="resumen">


            <h2>
                Detalle de compra
            </h2>



            <?php foreach ($detalles as $item): ?>


                <div class="fila-resumen">


                    <div>


                        <strong>

                            <?= htmlspecialchars(
                                $item['descripcion']
                            ) ?>

                        </strong>


                        <br>


                        <small>

                            Cantidad:

                            <?= (int) $item['cantidad'] ?>

                        </small>


                    </div>



                    <div>

                        ₡<?= number_format(
                            $item['precio_unitario'],
                            0,
                            ',',
                            '.'
                        ) ?>

                        c/u

                    </div>



                    <strong>

                        ₡<?= number_format(
                            $item['subtotal'],
                            0,
                            ',',
                            '.'
                        ) ?>

                    </strong>


                </div>


            <?php endforeach; ?>



            <hr>



            <div class="fila-resumen">


                <span>
                    Subtotal
                </span>


                <strong>

                    ₡<?= number_format(
                        $factura['subtotal'],
                        0,
                        ',',
                        '.'
                    ) ?>

                </strong>


            </div>



            <div class="fila-resumen">


                <span>
                    Envío
                </span>


                <strong>
                    Gratis
                </strong>


            </div>



            <div class="fila-resumen">


                <span>
                    Impuestos
                </span>


                <strong>

                    ₡<?= number_format(
                        $factura['impuesto_total'],
                        0,
                        ',',
                        '.'
                    ) ?>

                </strong>


            </div>



            <hr>



            <div class="fila-resumen total">


                <span>
                    Total
                </span>


                <strong>

                    ₡<?= number_format(
                        $factura['total'],
                        0,
                        ',',
                        '.'
                    ) ?>

                </strong>


            </div>


        </div>



        <br>



        <a
            class="boton"

            href="<?= $base ?>/pedidos/historial"
        >

            Ver mis pedidos

        </a>


        <a
            class="boton"

            href="<?= $base ?>/producto/catalogo"
        >

            Volver al inicio

        </a>


    </div>


</main>


<?php

require __DIR__ . '/../layouts/footer.php';

?>