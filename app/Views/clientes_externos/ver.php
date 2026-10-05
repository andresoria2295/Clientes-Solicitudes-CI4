<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="page-header">

    <div>
        <h1>Cliente externo</h1>

        <p class="subtitle">
            Información obtenida desde una API externa.
        </p>
    </div>

    <a href="<?= site_url('clientes') ?>" class="btn">
        Volver a Clientes
    </a>

</div>

<div class="panel">

    <div class="form-group">
        <label>ID externo</label>
        <p><?= esc($cliente['id'] ?? '') ?></p>
    </div>

    <div class="form-group">
        <label>Nombre</label>
        <p><?= esc($cliente['name'] ?? '') ?></p>
    </div>

    <div class="form-group">
        <label>Usuario</label>
        <p><?= esc($cliente['username'] ?? '') ?></p>
    </div>

    <div class="form-group">
        <label>Email</label>
        <p><?= esc($cliente['email'] ?? '') ?></p>
    </div>

    <div class="form-group">
        <label>Teléfono</label>
        <p><?= esc($cliente['phone'] ?? '') ?></p>
    </div>

    <div class="form-group">
        <label>Sitio web</label>
        <p><?= esc($cliente['website'] ?? '') ?></p>
    </div>

</div>

<?= $this->endSection() ?>