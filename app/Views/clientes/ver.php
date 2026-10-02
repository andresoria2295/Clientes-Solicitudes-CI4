<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- ENCABEZADO -->

<div class="page-header">

    <div>
        <h1>Detalle del cliente</h1>

        <p class="subtitle">
            Información individual del registro.
        </p>
    </div>

    <a href="<?= site_url('clientes') ?>" class="btn">
        Volver al listado
    </a>

</div>


<!-- INFORMACIÓN DEL CLIENTE -->

<div class="panel">

    <h2>
        <?= esc($cliente->nombre) ?>
        <?= esc($cliente->apellido ?? '') ?>
    </h2>

    <p class="subtitle">
        Identificador del cliente:
        #<?= esc($cliente->id) ?>
    </p>

    <br>

    <div class="form-group">

        <label>Nombre</label>

        <p><?= esc($cliente->nombre) ?></p>

    </div>

    <div class="form-group">

        <label>Apellido</label>

        <p><?= esc($cliente->apellido ?: 'No informado') ?></p>

    </div>

    <div class="form-group">

        <label>Correo electrónico</label>

        <p><?= esc($cliente->email) ?></p>

    </div>

    <div class="form-group">

        <label>Teléfono</label>

        <p><?= esc($cliente->telefono ?: 'No informado') ?></p>

    </div>

    <!-- ACCIONES -->

    <div class="action-group">

        <a
            href="<?= site_url('clientes/' . $cliente->id . '/editar') ?>"
            class="btn btn-primary"
        >
            Editar cliente
        </a>

        <a href="<?= site_url('clientes') ?>" class="btn">
            Volver
        </a>

    </div>

</div>

<?= $this->endSection() ?>
