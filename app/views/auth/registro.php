<?php
$titulo = 'SideGeek | Registro';
require __DIR__ . '/../layouts/header.php';
?>

<main class="contenedor-formulario">
    <form method="POST" action="<?= $base ?>/registro" class="formulario">
        <p class="etiqueta">Crea tu cuenta</p>
        <h1>Registro</h1>

        <?php if (!empty($error)): ?>
            <p class="mensaje error">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <label for="nombre">Nombre completo</label>
        <input id="nombre" name="nombre" type="text" required>

        <label for="correo">Correo electrónico</label>
        <input id="correo" name="correo" type="email" required>

        <label for="contrasena">Contraseña</label>
        <input id="contrasena" name="contrasena" type="password" required>

        <label for="confirmar">Confirmar contraseña</label>
        <input id="confirmar" name="confirmar" type="password" required>

        <button class="boton boton-ancho" type="submit">
            Crear cuenta
        </button>
    </form>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>