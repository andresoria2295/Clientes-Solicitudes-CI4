
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- ENCABEZADO -->

<div class="page-header">

    <div>

        <h1>Editar cliente</h1>

        <p class="subtitle">
            Modificando el cliente ID:
            <strong>#<?= esc($cliente->id) ?></strong>
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

        <strong>Revisá los siguientes errores:</strong>

        <ul>

            <?php foreach ($errors as $error): ?>

                <li><?= esc($error) ?></li>

            <?php endforeach; ?>

        </ul>

    </div>

<?php endif; ?>


<!-- FORMULARIO DE EDICIÓN -->

<div class="panel form-panel">

    <form
        action="<?= site_url('clientes/' . $cliente->id . '/actualizar') ?>"
        method="POST"
    >

        <?= csrf_field() ?>


        <!-- NOMBRE -->

        <div class="form-group">

            <label for="nombre">
                Nombre *
            </label>

            <input
                type="text"
                name="nombre"
                id="nombre"
                maxlength="100"
                value="<?= esc(old('nombre') ?? $cliente->nombre) ?>"
                required
            >

        </div>


        <!-- APELLIDO -->

        <div class="form-group">

            <label for="apellido">
                Apellido
            </label>

            <input
                type="text"
                name="apellido"
                id="apellido"
                maxlength="100"
                value="<?= esc(old('apellido') ?? $cliente->apellido ?? '') ?>"
            >

        </div>


        <!-- EMAIL -->

        <div class="form-group">

            <label for="email">
                Correo electrónico *
            </label>

            <input
                type="email"
                name="email"
                id="email"
                maxlength="190"
                value="<?= esc(old('email') ?? $cliente->email) ?>"
                required
            >

        </div>


        <!-- TELÉFONO -->

        <div class="form-group">

            <label for="telefono">
                Teléfono
            </label>

            <input
                type="text"
                name="telefono"
                id="telefono"
                maxlength="30"
                value="<?= esc(old('telefono') ?? $cliente->telefono ?? '') ?>"
            >

        </div>


        <!-- ACCIONES -->

        <div class="action-group">

            <button
                type="submit"
                class="btn btn-primary"
            >
                Guardar cambios
            </button>

            <a
                href="<?= site_url('clientes') ?>"
                class="btn"
            >
                Cancelar
            </a>

        </div>

    </form>

</div>

<?= $this->endSection() ?>

