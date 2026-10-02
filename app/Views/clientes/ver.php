<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detalle del cliente</title>
</head>

<body>

    <h1>Información del cliente</h1>

    <p>
        <strong>ID:</strong>
        <?= esc($cliente['id']) ?>
    </p>

    <p>
        <strong>Nombre:</strong>
        <?= esc($cliente['nombre']) ?>
    </p>

    <p>
        <strong>Apellido:</strong>
        <?= esc($cliente['apellido'] ?? '') ?>
    </p>

    <p>
        <strong>Email:</strong>
        <?= esc($cliente['email']) ?>
    </p>

    <p>
        <strong>Teléfono:</strong>
        <?= esc($cliente['telefono'] ?? '') ?>
    </p>

    <br>

    <a href="<?= site_url('clientes') ?>">
        Volver al listado
    </a>

</body>
</html>