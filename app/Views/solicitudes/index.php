<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- ENCABEZADO -->

<div class="page-header">

    <div>
        <h1>Solicitudes registradas</h1>

        <p class="subtitle">
            Administración y seguimiento de solicitudes.
        </p>
    </div>

    <a
        href="<?= site_url('solicitudes/nuevo') ?>"
        class="btn btn-primary"
    >
        + Nueva solicitud
    </a>

</div>


<!-- MENSAJES DE ÉXITO -->

<?php $mensaje = session()->getFlashdata('success'); ?>

<?php if ($mensaje): ?>

    <div class="alert alert-success" role="status">
        <?= esc($mensaje) ?>
    </div>

<?php endif; ?>


<!-- MENSAJES DE ERROR -->

<?php $error = session()->getFlashdata('error'); ?>

<?php if ($error): ?>

    <div class="alert alert-error" role="alert">
        <?= esc($error) ?>
    </div>

<?php endif; ?>


<!-- LISTADO DE SOLICITUDES -->

<div class="panel">

    <?php if (!empty($solicitudes)): ?>

        <div class="table-wrap">

            <table class="data-table">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Asunto</th>
                        <th>Cliente</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($solicitudes as $solicitud): ?>

                        <tr>

                            <!-- IDENTIFICADOR -->

                            <td>
                                <?= esc($solicitud['id']) ?>
                            </td>

                            <!-- ASUNTO -->

                            <td>
                                <?= esc($solicitud['asunto']) ?>
                            </td>

                            <!-- CLIENTE ASOCIADO -->

                            <td>
                                <?= esc($solicitud['cliente_nombre']) ?>
                                <?= esc($solicitud['cliente_apellido'] ?? '') ?>
                            </td>


                            <!-- ESTADO -->

                            <td>
                                <?php
                                    $estadoClase = match (trim($solicitud['estado'])) {
                                        'Pendiente'  => 'status-pending',
                                        'En proceso' => 'status-progress',
                                        'Resuelta'   => 'status-resolved',
                                        default      => 'status-pending'
                                    };
                                ?>

                                <span class="status-badge <?= esc($estadoClase) ?>">
                                    <?= esc($solicitud['estado']) ?>
                                </span>
                            </td>


                            <!-- ACCIONES -->

                            <td>

                                <div class="action-group">

                                    <!-- VER -->

                                    <a
                                        href="<?= site_url('solicitudes/' . $solicitud['id']) ?>"
                                        class="btn"
                                    >
                                        Ver
                                    </a>

                                    <!-- EDITAR -->

                                    <a
                                        href="<?= site_url('solicitudes/' . $solicitud['id'] . '/editar') ?>"
                                        class="btn"
                                    >
                                        Editar
                                    </a>

                                    <!-- ELIMINAR -->

                                    <form
                                        action="<?= site_url('solicitudes/' . $solicitud['id'] . '/eliminar') ?>"
                                        method="POST"
                                        onsubmit="return confirm('¿Estás seguro de eliminar esta solicitud? Esta acción no se puede deshacer.');"
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

        <!-- SIN SOLICITUDES REGISTRADAS -->

        <div class="empty-state">

            <p>
                Todavía no existen solicitudes registradas.
            </p>

            <a
                href="<?= site_url('solicitudes/nuevo') ?>"
                class="btn btn-primary"
            >
                Registrar primera solicitud
            </a>

        </div>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>
