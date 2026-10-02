
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar cliente</title>
</head>

<body>

    <h1>Editar cliente</h1>

    <!-- Mostrar errores de validación -->
    <?php $errors = session()->getFlashdata('errors') ?? []; ?>

    <?php if (!empty($errors)): ?>

        <div role="alert">
            <h3>Revisá los siguientes errores:</h3>

            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

    <?php endif; ?>

    <p>
        Estás modificando el cliente ID:
        <strong><?= esc($cliente['id']) ?></strong>
    </p>

    <!-- Formulario de edición -->
    <form
        action="<?= site_url('clientes/' . $cliente['id'] . '/actualizar') ?>"
        method="POST"
    >

        <?= csrf_field() ?>

        <label>Nombre:</label>
        <input
            type="text"
            name="nombre"
            value="<?= esc(old('nombre') ?? $cliente['nombre']) ?>"
            required
        >

        <br><br>

        <label>Apellido:</label>
        <input
            type="text"
            name="apellido"
            value="<?= esc(old('apellido') ?? $cliente['apellido'] ?? '') ?>"
        >

        <br><br>

        <label>Email:</label>
        <input
            type="email"
            name="email"
            value="<?= esc(old('email') ?? $cliente['email']) ?>"
            required
        >

        <br><br>

        <label>Teléfono:</label>
        <input
            type="text"
            name="telefono"
            value="<?= esc(old('telefono') ?? $cliente['telefono'] ?? '') ?>"
        >

        <br><br>

        <button type="submit">Guardar cambios</button>

    </form>

    <br>

    <a href="<?= site_url('clientes') ?>">
        Cancelar y volver al listado
    </a>

</body>
</html>
