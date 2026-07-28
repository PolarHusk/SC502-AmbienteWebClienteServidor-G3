<?php $base = '/SC502-AmbienteWebClienteServidor-G3/public'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'SideGeek' ?></title>

    <link rel="stylesheet" href="<?= $base ?>/css/estilos.css">
</head>
<body>

<header class="header">
    <a class="logo" href="<?= $base ?>/">
        Side<span>Geek</span>
    </a>

    <nav class="nav">
        <a href="<?= $base ?>/">Inicio</a>
        <a href="<?= $base ?>/productos">Catálogo</a>

        <?php if (isset($_SESSION['usuario'])): ?>
            <a href="<?= $base ?>/pedidos/historial">Mis pedidos</a>
            <a href="<?= $base ?>/logout">Cerrar sesión</a>
        <?php else: ?>
            <a href="<?= $base ?>/login">Iniciar sesión</a>
        <?php endif; ?>

        <a href="<?= $base ?>/carrito">
            Carrito
            <span id="contador-carrito">
                <?= count($_SESSION['carrito'] ?? []) ?>
            </span>
        </a>
    </nav>
</header>