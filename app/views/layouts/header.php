<?php

$base = '/SC502-AmbienteWebClienteServidor-G3-main/public';


$esAuth = isset($titulo) && (
    $titulo === 'SideGeek | Iniciar sesión' ||
    $titulo === 'SideGeek | Registro'
);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $titulo ?? 'SideGeek' ?></title>

    <link rel="stylesheet" href="<?= $base ?>/css/style.css">
    <link rel="stylesheet" href="<?= $base ?>/css/carrito.css">

</head>

<body>

<header class="header">

    <a class="logo" href="<?= $base ?>/">
        Side<span>Geek</span>
    </a>

    <?php if (!$esAuth): ?>

        <nav class="nav">

            <a href="<?= $base ?>/producto/catalogo">
                Inicio
            </a>

            <?php if (isset($_SESSION['usuario'])): ?>

                <span class="usuario-nombre">
                    Hola, <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?>
                </span>

                <a href="<?= $base ?>/pedidos/historial">
                    Pedidos
                </a>

                <a href="<?= $base ?>/auth/logout">
                    Cerrar sesión
                </a>

                <a href="<?= $base ?>/carrito">
                    Carrito

                    <span id="contador-carrito">
                        <?= (int) ($_SESSION['carrito_cantidad'] ?? 0) ?>
                    </span>
                </a>

            <?php else: ?>

                <a href="<?= $base ?>/auth/index">
                    Iniciar sesión
                </a>

            <?php endif; ?>

        </nav>

    <?php endif; ?>


</header>