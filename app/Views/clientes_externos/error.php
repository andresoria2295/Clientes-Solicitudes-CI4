<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="page-header">

    <div>
        <h1>Error al consultar API externa</h1>

        <p class="subtitle">
            No pudimos recuperar la información solicitada.
        </p>
    </div>

</div>

<div class="alert alert-error" role="alert">

    <?= esc($mensaje) ?>

</div>

<a href="<?= site_url('clientes') ?>" class="btn">
    Volver a Clientes
</a>

<?= $this->endSection() ?>