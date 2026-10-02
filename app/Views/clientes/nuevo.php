<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo cliente</title>
</head>

<body>

    <h1>Registrar nuevo cliente</h1>

    <?php $errors = session()->getFlashdata('errors') ?? []; ?>

    <?php if (!empty($errors)): ?>

        <div>
            <h3>Revisá los siguientes campos:</h3>

            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

    <?php endif; ?>
    <form action="<?= site_url('clientes') ?>" method="POST">

        <?= csrf_field() ?>

        <label>Nombre:</label>
        <input
            type="text"
            name="nombre"
            value="<?= esc(old('nombre') ?? '') ?>"
            required
        >

        <br><br>

        <label>Apellido:</label>
        <input
            type="text"
            name="apellido"
            value="<?= esc(old('apellido') ?? '') ?>"
        >

        <br><br>

        <label>Email:</label>
        <input
            type="email"
            name="email"
            value="<?= esc(old('email') ?? '') ?>"
            required
        >

        <br><br>

        <label>Teléfono:</label>
        <input
            type="text"
            name="telefono"
            value="<?= esc(old('telefono') ?? '') ?>"
        >
        <br><br>

        <button type="submit">Guardar cliente</button>

    </form>

    <br>

    <a href="<?= site_url('clientes') ?>">
        Volver al listado
    </a>

</body>
</html>