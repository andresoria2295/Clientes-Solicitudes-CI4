
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Gestión de Clientes y Solicitudes</title>

    <!-- CSS compartido -->
    <link
    rel="stylesheet"
    href="<?= base_url('assets/css/app.css') ?>?v=2"
    >
</head>

<body>

    <!-- ENCABEZADO GENERAL -->

    <header class="topbar">

        <div class="container topbar-inner">

            <a href="<?= site_url('clientes') ?>" class="brand">

                <span class="brand-mark">CS</span>

                <span>
                    Clientes y Solicitudes
                    <small>Sistema de gestión</small>
                </span>

            </a>

            <!-- NAVEGACIÓN -->

            <nav class="nav" aria-label="Navegación principal">

                <a href="<?= site_url('clientes') ?>">
                    Clientes
                </a>

                <a href="<?= site_url('solicitudes') ?>">
                    Solicitudes
                </a>

            </nav>

        </div>

    </header>


    <!-- CONTENIDO VARIABLE -->

    <main class="container main-content">

        <?= $this->renderSection('content') ?>

    </main>


    <!-- PIE DE PÁGINA -->

    <footer class="footer">

        Clientes y Solicitudes · Práctica CodeIgniter 4

    </footer>

</body>
</html>
