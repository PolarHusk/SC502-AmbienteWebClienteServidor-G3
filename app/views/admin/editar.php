<?php

$titulo = 'SideGeek | Editar producto';
$base = '/SC502-AmbienteWebClienteServidor-G3-main/public';

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title><?= $titulo ?></title>

    <link
        rel="stylesheet"
        href="<?= $base ?>/css/style.css"
    >

    <link
        rel="stylesheet"
        href="<?= $base ?>/css/admin.css"
    >

</head>

<body class="admin-body">

<header class="admin-header">

    <a
        class="logo"
        href="<?= $base ?>/admin/index"
    >
        Side<span>Geek</span>
    </a>

    <div class="admin-header-derecha">

        <span>
            Hola,
            <?= htmlspecialchars(
                $_SESSION['usuario']['nombre'] ?? 'Admin'
            ) ?>
        </span>

        <a href="<?= $base ?>/auth/logout">
            Cerrar sesión
        </a>

    </div>

</header>


<main class="admin-main">

    <section class="panel-admin formulario-producto">

        <p class="etiqueta">
            PRODUCTOS
        </p>

        <h1>
            Editar producto
        </h1>

        <p class="texto-secundario">
            Modifique la información del producto.
        </p>


        <form
            method="POST"
            action="<?= $base ?>/admin/editar"
            class="form-producto"
        >

            <input
                type="hidden"
                name="id"
                value="<?= (int) $producto['id'] ?>"
            >


            <div class="campo campo-completo">

                <label for="nombre">
                    Nombre
                </label>

                <input
                    id="nombre"
                    type="text"
                    name="nombre"
                    value="<?= htmlspecialchars($producto['nombre']) ?>"
                    required
                >

            </div>


            <div class="campo campo-completo">

                <label for="categoria_id">
                    Categoría
                </label>

                <select
                    id="categoria_id"
                    name="categoria_id"
                    required
                >

                    <?php foreach ($categorias ?? [] as $categoria): ?>

                        <option
                            value="<?= (int) $categoria['id'] ?>"

                            <?php if (
                                (int) $producto['categoria_id'] ===
                                (int) $categoria['id']
                            ): ?>

                                selected

                            <?php endif; ?>
                        >

                            <?= htmlspecialchars(
                                $categoria['nombre']
                            ) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <div class="campo campo-completo">

                <label for="descripcion">
                    Descripción
                </label>

                <textarea
                    id="descripcion"
                    name="descripcion"
                    rows="5"
                    required
                ><?= htmlspecialchars($producto['descripcion']) ?></textarea>

            </div>


            <div class="campo campo-completo">

                <label for="imagen_principal_url">
                    URL de imagen
                </label>

                <input
                    id="imagen_principal_url"
                    type="text"
                    name="imagen_principal_url"
                    value="<?= htmlspecialchars(
                        $producto['imagen_principal_url'] ?? ''
                    ) ?>"
                >

            </div>


            <div class="campo">

                <label for="precio">
                    Precio
                </label>

                <input
                    id="precio"
                    type="number"
                    name="precio"
                    min="1"
                    step="0.01"
                    value="<?= htmlspecialchars($producto['precio']) ?>"
                    required
                >

            </div>


            <div class="campo">

                <label for="stock">
                    Stock
                </label>

                <input
                    id="stock"
                    type="number"
                    name="stock"
                    min="0"
                    value="<?= (int) $producto['stock'] ?>"
                    required
                >

            </div>


            <div class="campo">

                <label for="descuento_porcentaje">
                    Descuento %
                </label>

                <input
                    id="descuento_porcentaje"
                    type="number"
                    name="descuento_porcentaje"
                    min="0"
                    max="100"
                    value="<?= (int) $producto['descuento_porcentaje'] ?>"
                >

            </div>


            <div class="campo campo-check">

                <label>

                    <input
                        type="checkbox"
                        name="es_nuevo_lanzamiento"

                        <?php if (
                            (int) $producto['es_nuevo_lanzamiento'] === 1
                        ): ?>

                            checked

                        <?php endif; ?>
                    >

                    <span>
                        Nuevo lanzamiento
                    </span>

                </label>

            </div>


            <div class="acciones-formulario">

                <button
                    class="boton"
                    type="submit"
                >
                    Guardar cambios
                </button>


                <a
                    class="boton boton-secundario"
                    href="<?= $base ?>/admin/index"
                >
                    Cancelar
                </a>

            </div>

        </form>

    </section>

</main>

</body>

</html>