<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <!-- ENCABEZADO DE LA PÁGINA -->

    <div class="page-header">

        <div>
            <h1>Clientes registrados</h1>

            <p class="subtitle">
                Administración y consulta de clientes.
            </p>
        </div>

        <a
            href="<?= site_url('clientes/nuevo') ?>"
            class="btn btn-primary"
        >
            + Nuevo cliente
        </a>

    </div>


    <!-- MENSAJES -->

    <?php $mensaje = session()->getFlashdata('success'); ?>

    <?php if ($mensaje): ?>

        <div class="alert alert-success" role="status">
            <?= esc($mensaje) ?>
        </div>

    <?php endif; ?>


    <?php $error = session()->getFlashdata('error'); ?>

    <?php if ($error): ?>

        <div class="alert alert-error" role="alert">
            <?= esc($error) ?>
        </div>

    <?php endif; ?>


    <!-- TABLA DE CLIENTES -->

    <div class="panel">

        <?php if (!empty($clientes)): ?>

            <div class="table-wrap">

                <table class="data-table">

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

                                <td>

                                    <div class="action-group">

                                        <!-- VER -->

                                        <a
                                            class="btn"
                                            href="<?= site_url('clientes/' . $cliente['id']) ?>"
                                        >
                                            Ver
                                        </a>

                                        <!-- EDITAR -->

                                        <a
                                            class="btn"
                                            href="<?= site_url('clientes/' . $cliente['id'] . '/editar') ?>"
                                        >
                                            Editar
                                        </a>

                                        <!-- ELIMINAR -->

                                        <form
                                            action="<?= site_url('clientes/' . $cliente['id'] . '/eliminar') ?>"
                                            method="POST"
                                            onsubmit="return confirm('¿Estás seguro de eliminar este cliente?');"
                                        >

                                            <?= csrf_field() ?>

                                            <button
                                                type="submit"
                                                class="btn btn-danger"
                                            >
                                                Eliminar
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php else: ?>

            <div class="empty-state">

                Todavía no existen clientes registrados.

            </div>

        <?php endif; ?>

    </div>

<?= $this->endSection() ?>
