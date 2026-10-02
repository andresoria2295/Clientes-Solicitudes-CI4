<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Listado de clientes</title>
</head>

<body>

    <h1>Clientes registrados</h1>

    <!-- MENSAJE DE ÉXITO -->

    <?php $mensaje = session()->getFlashdata('success'); ?>

    <?php if ($mensaje): ?>

        <div role="status"
             style="padding: 12px; background: #e8f5e9;
                    color: #1b5e20; margin-bottom: 15px;
                    border: 1px solid #81c784;">

            <strong>✅ <?= esc($mensaje) ?></strong>

        </div>

    <?php endif; ?>


    <!-- MENSAJE DE ERROR -->

    <?php $error = session()->getFlashdata('error'); ?>

    <?php if ($error): ?>

        <div role="alert"
             style="padding: 12px; background: #ffebee;
                    color: #b71c1c; margin-bottom: 15px;
                    border: 1px solid #ef9a9a;">

            <strong>⚠ <?= esc($error) ?></strong>

        </div>

    <?php endif; ?>


    <!-- ENLACE PARA REGISTRAR CLIENTES -->

    <a href="<?= site_url('clientes/nuevo') ?>">
        + Agregar nuevo cliente
    </a>

    <br><br>


    <!-- LISTADO DE CLIENTES -->

    <?php if (!empty($clientes)): ?>

        <table border="1" cellpadding="10">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($clientes as $cliente): ?>

                    <tr>
                        <td><?= esc($cliente['id']) ?></td>
                        <td><?= esc($cliente['nombre']) ?></td>
                        <td><?= esc($cliente['apellido']) ?></td>
                        <td><?= esc($cliente['email']) ?></td>
                        <td><?= esc($cliente['telefono']) ?></td>

                        <!-- ACCIONES -->

                        <td>

                            <!-- Consultar -->

                            <a href="<?= site_url('clientes/' . $cliente['id']) ?>">
                                Ver
                            </a>

                            |

                            <!-- Editar -->

                            <a href="<?= site_url('clientes/' . $cliente['id'] . '/editar') ?>">
                                Editar
                            </a>

                            |

                            <!-- Eliminar -->

                            <form
                                action="<?= site_url('clientes/' . $cliente['id'] . '/eliminar') ?>"
                                method="POST"
                                style="display:inline;"
                                onsubmit="return confirm('¿Estás seguro de eliminar este cliente?');"
                            >

                                <?= csrf_field() ?>

                                <button type="submit">
                                    Eliminar
                                </button>

                            </form>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    <?php else: ?>

        <p>No hay clientes registrados.</p>

    <?php endif; ?>

</body>
</html>

