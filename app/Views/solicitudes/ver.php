
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- ENCABEZADO -->

<div class="page-header">

    <div>
        <h1>Detalle de solicitud</h1>

        <p class="subtitle">
            Información individual del registro
            #<?= esc($solicitud->id) ?>
        </p>
    </div>

    <a href="<?= site_url('solicitudes') ?>" class="btn">
        Volver al listado
    </a>

</div>


<!-- DETALLE DE LA SOLICITUD -->

<div class="panel">

    <h2>
        <?= esc($solicitud->asunto) ?>
    </h2>

    <p class="subtitle">
        Identificador de solicitud:
        #<?= esc($solicitud->id) ?>
    </p>

    <br>


    <!-- CLIENTE ASOCIADO -->

    <div class="form-group">

        <label>Cliente asociado</label>

        <p>

            <a href="<?= site_url('clientes/' . $solicitud->cliente_id) ?>">

                <?= esc($cliente['nombre']) ?>
                <?= esc($cliente['apellido'] ?? '') ?>

            </a>

        </p>

    </div>


    <!-- ASUNTO -->

    <div class="form-group">

        <label>Asunto</label>

        <p><?= esc($solicitud->asunto) ?></p>

    </div>


    <!-- DESCRIPCIÓN -->

    <div class="form-group">

        <label>Descripción</label>

        <p>
            <?= nl2br(esc($solicitud->descripcion)) ?>
        </p>

    </div>


    <!-- ESTADO -->

    <div class="form-group">

        <label>Estado actual</label>

        <?php

        $estadoClase = match ($solicitud->estado) {
            'Pendiente'   => 'status-pending',
            'En proceso' => 'status-progress',
            'Resuelta'   => 'status-resolved',
            default      => 'status-pending'
        };

        ?>

        <p>
            <span class="status-badge <?= esc($estadoClase) ?>">

                <?= esc($solicitud->estado) ?>

            </span>
        </p>

    </div>


    <!-- ACCIONES -->

    <div class="action-group">

        <a
            href="<?= site_url('solicitudes/' . $solicitud->id . '/editar') ?>"
            class="btn btn-primary"
        >
            Editar solicitud
        </a>

        <a href="<?= site_url('solicitudes') ?>" class="btn">
            Volver
        </a>

    </div>

</div>

<?= $this->endSection() ?>
