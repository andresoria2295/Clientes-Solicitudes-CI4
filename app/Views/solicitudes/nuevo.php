
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- ENCABEZADO -->

<div class="page-header">

    <div>

        <h1>Registrar nueva solicitud</h1>

        <p class="subtitle">
            Completá la información para registrar una solicitud.
        </p>

    </div>

    <a href="<?= site_url('solicitudes') ?>" class="btn">
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

<div class="panel form-panel">

    <form
        action="<?= site_url('solicitudes') ?>"
        method="POST"
    >

        <?= csrf_field() ?>

        <!-- CLIENTE -->

        <div class="form-group">

            <label for="cliente_id">
                Cliente *
            </label>

            <select
                name="cliente_id"
                id="cliente_id"
                required
            >

                <option value="">
                    -- Seleccionar un cliente --
                </option>

                <?php foreach ($clientes as $cliente): ?>

                    <option
                        value="<?= esc($cliente['id']) ?>"

                        <?= (string) old('cliente_id') === (string) $cliente['id']
                            ? 'selected'
                            : '' ?>
                    >

                        <?= esc($cliente['nombre']) ?>
                        <?= esc($cliente['apellido'] ?? '') ?>

                    </option>

                <?php endforeach; ?>

            </select>

        </div>


        <!-- ASUNTO -->

        <div class="form-group">

            <label for="asunto">
                Asunto *
            </label>

            <input
                type="text"
                name="asunto"
                id="asunto"
                maxlength="150"
                value="<?= esc(old('asunto') ?? '') ?>"
                placeholder="Ej.: Problema de acceso al sistema"
                required
            >

        </div>


        <!-- DESCRIPCIÓN -->

        <div class="form-group">

            <label for="descripcion">
                Descripción *
            </label>

            <textarea
                name="descripcion"
                id="descripcion"
                rows="5"
                placeholder="Describí brevemente la solicitud..."
                required
><?= esc(old('descripcion') ?? '') ?></textarea>

        </div>


        <!-- ESTADO -->

        <div class="form-group">

            <label for="estado">
                Estado *
            </label>

            <select
                name="estado"
                id="estado"
                required
            >

                <?php foreach (['Pendiente', 'En proceso', 'Resuelta'] as $estado): ?>

                    <option
                        value="<?= esc($estado) ?>"

                        <?= (old('estado') ?? 'Pendiente') === $estado
                            ? 'selected'
                            : '' ?>
                    >

                        <?= esc($estado) ?>

                    </option>

                <?php endforeach; ?>

            </select>

        </div>


        <!-- ACCIONES -->

        <div class="action-group">

            <button
                type="submit"
                class="btn btn-primary"
                <?= empty($clientes) ? 'disabled' : '' ?>
            >
                Guardar solicitud
            </button>

            <a href="<?= site_url('solicitudes') ?>" class="btn">
                Cancelar
            </a>

        </div>

    </form>


    <!-- CONTROL SI NO EXISTEN CLIENTES -->

    <?php if (empty($clientes)): ?>

        <div class="alert alert-error" role="alert">

            Primero debés registrar al menos un cliente
            para poder crear una solicitud.

            <br><br>

            <a href="<?= site_url('clientes/nuevo') ?>">
                Registrar cliente
            </a>

        </div>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>


