<?php

$titulo = 'SideGeek | Iniciar sesión';

require __DIR__ . '/../layouts/header.php';

?>

<main class="contenedor-formulario">


    <form method="POST"
          action="<?= $base ?>/auth/login"
          class="formulario">

        <p class="etiqueta">Bienvenido</p>

        <h1>Iniciar sesión</h1>

        <?php if (!empty($error)): ?>

            <p class="mensaje error">
                <?= htmlspecialchars($error) ?>
            </p>

        <?php endif; ?>


        <label for="correo">
            Correo electrónico
        </label>

        <input
            id="correo"
            name="correo"
            type="email"
            required
            value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>"
        >


        <label for="contrasena">
            Contraseña
        </label>

        <input
            id="contrasena"
            name="contrasena"
            type="password"
            required
        >


        <button class="boton boton-ancho" type="submit">
            Ingresar
        </button>


     <p class="registro-login">
    ¿No tienes cuenta?

    <a
        class="enlace"
        href="<?= $base ?>/auth/registro"
    >
        Regístrate
    </a>
</p>


    </form>

</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>