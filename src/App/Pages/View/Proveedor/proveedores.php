<?php
require_once __DIR__ . '/../../../../../vendor/autoload.php';

require_once __DIR__ . '/../Shared/start_session.php';

if (!isset($_SESSION['is_admin'])) {
    header("Location: ../Auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestionar Proveedores</title>

    <link rel="shortcut icon" href="../../../../../public/img/LogoBlanco.png">
    <link rel="stylesheet" href="../../../../../public/css/libs/vendor.bundle.css">
    <link rel="stylesheet" href="../../../../../public/css/Proveedor/proveedores.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css">
</head>

<body>

    <?php include '../Shared/header.php'; ?>

    <div class="container-fluid px-4 py-4">
        <div class="table-container table-responsive">
            <div class="d-flex justify-content-between align-items-center mb-5 border-bottom pb-3 mt-2">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-users me-2 fs-4"></i>
                    <h5 class="m-0 fw-semibold text-dark">Gestionar Proveedores</h5>
                </div>
                <button type="button" class="btn btn-custom-teal" id="btnNuevoProveedor">
                    <i class="fa-solid fa-plus me-1"></i> Nuevo Proveedor
                </button>
            </div>

            <table id="tabla-proveedores" class="table table-striped table-bordered table-sm dt-responsive nowrap" style="width:100%">
                <thead class="custom-thead">
                    <tr>
                        <th style="width: 13%;" class="text-center">Nro. Acreedor</th>
                        <th style="width: 20%;" class="text-center">Nombre</th>
                        <th style="width: 14%;" class="text-center">Tipo Proveedor</th>
                        <th style="width: 27%;" class="text-center">Creado por el Administrador</th>
                        <th style="width: 14%;" class="text-center">Estado</th>
                        <th style="width: 12%;" class="text-center"></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <?php include '../Shared/footer.php'; ?>

    <script src="../../../../../public/js/libs/vendor.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script src="../../../../../public/js/Proveedor/proveedores.js"></script>

</body>

</html>