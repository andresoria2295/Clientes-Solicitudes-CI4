
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- ENCABEZADO -->

<div class="page-header">

    <div>
        <h1>Registrar nuevo cliente</h1>

        <p class="subtitle">
            Completá la información del cliente.
        </p>
    </div>

    <a href="<?= site_url('clientes') ?>" class="btn">
        Volver al listado
    </a>

</div>


<!-- ERRORES DE VALIDACIÓN -->

<?php $errors = session()->getFlashdata('errors') ?? []; ?>

<?php if (!empty($errors)): ?>

    <div class="alert alert-error" role="alert">

        <strong>Revisá los siguientes campos:</strong>

        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>

    </div>

<?php endif; ?>


<!-- FORMULARIO -->

<div class="panel">

    <form
        action="<?= site_url('clientes') ?>"
        method="POST"
    >

        <?= csrf_field() ?>

        <!-- NOMBRE -->

        <div class="form-group">

            <label for="nombre">Nombre *</label>

            <input
                type="text"
                id="nombre"
                name="nombre"
                maxlength="100"
                value="<?= esc(old('nombre') ?? '') ?>"
                required
            >

        </div>

        <!-- APELLIDO -->

        <div class="form-group">

            <label for="apellido">Apellido</label>

            <input
                type="text"
                id="apellido"
                name="apellido"
                maxlength="100"
                value="<?= esc(old('apellido') ?? '') ?>"
            >

        </div>

        <!-- EMAIL -->

        <div class="form-group">

            <label for="email">Correo electrónico *</label>

            <input
                type="email"
                id="email"
                name="email"
                maxlength="190"
                value="<?= esc(old('email') ?? '') ?>"
                required
            >

        </div>

        <!-- TELÉFONO -->

        <div class="form-group">

            <label for="telefono">Teléfono</label>

            <input
                type="tel"
                id="telefono"
                name="telefono"
                maxlength="30"
                value="<?= esc(old('telefono') ?? '') ?>"
            >

        </div>

        <!-- ACCIONES -->

        <div class="action-group">

            <button type="submit" class="btn btn-primary">
                Guardar cliente
            </button>

            <a href="<?= site_url('clientes') ?>" class="btn">
                Cancelar
            </a>

        </div>

    </form>

</div>

<?= $this->endSection() ?>
