
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- ENCABEZADO -->

<div class="page-header">

    <div>
        <h1>Editar solicitud</h1>

        <p class="subtitle">
            Modificando la solicitud ID:
            <strong>#<?= esc($solicitud['id']) ?></strong>
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
        action="<?= site_url('solicitudes/' . $solicitud['id'] . '/actualizar') ?>"
        method="POST"
    >

        <?= csrf_field() ?>

        <!-- CLIENTE ASOCIADO -->

        <div class="form-group">

            <label for="cliente_id">Cliente *</label>

            <select
                name="cliente_id"
                id="cliente_id"
                required
            >

                <?php foreach ($clientes as $cliente): ?>

                    <option
                        value="<?= esc($cliente['id']) ?>"

                        <?= (string) (old('cliente_id') ?? $solicitud['cliente_id'])
                            === (string) $cliente['id']
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

            <label for="asunto">Asunto *</label>

            <input
                type="text"
                name="asunto"
                id="asunto"
                maxlength="150"
                value="<?= esc(old('asunto') ?? $solicitud['asunto']) ?>"
                required
            >

        </div>


        <!-- DESCRIPCIÓN -->

        <div class="form-group">

            <label for="descripcion">Descripción *</label>

            <textarea
                name="descripcion"
                id="descripcion"
                rows="5"
                required
><?= esc(old('descripcion') ?? $solicitud['descripcion']) ?></textarea>

        </div>


        <!-- ESTADO -->

        <div class="form-group">

            <label for="estado">Estado *</label>

            <select
                name="estado"
                id="estado"
                required
            >

                <?php foreach (['Pendiente', 'En proceso', 'Resuelta'] as $estado): ?>

                    <option
                        value="<?= esc($estado) ?>"

                        <?= (old('estado') ?? $solicitud['estado']) === $estado
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

            <button type="submit" class="btn btn-primary">
                Guardar cambios
            </button>

            <a href="<?= site_url('solicitudes') ?>" class="btn">
                Cancelar
            </a>

        </div>

    </form>

</div>

<?= $this->endSection() ?>

