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
    <link rel="stylesheet" href="../../../../../public/css/libs/choices.min.css">
    <link rel="stylesheet" href="../../../../../public/css/Administrador/proveedores.css">
    <link rel="stylesheet" href="../../../../../public/css/Administrador/crearProveedor.css">
    <link rel="stylesheet" href="../../../../../public/css/Administrador/editarProveedor.css">
    <link rel="stylesheet" href="../../../../../public/css/Administrador/filtrosProveedor.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0.16/dist/fancybox.css">
</head>

<body>

    <?php include '../Shared/header.php'; ?>

    <div class="container-fluid px-3 px-md-4 py-3">
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3 mt-1">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-users me-2 fs-4"></i>
                    <h5 class="m-0 fw-semibold text-dark">Gestionar Proveedores</h5>
                </div>
                <button type="button" class="btn btn-custom-teal" id="btnNuevoProveedor">
                    <i class="fa-solid fa-user-plus me-1"></i> Agregar Proveedor
                </button>
            </div>

            <div class="d-flex align-items-center mb-3">
                <button type="button" class="btn btn-exportar-excel" id="btnDescargarExcel" title="Descargar datos de la tabla a Excel">
                    <i class="fa-solid fa-file-excel"></i>
                    <span>Descargar Excel</span>
                </button>
            </div>

            <div class="active-filters-wrapper d-none mb-3" id="activeFiltersWrapper">
                <div class="d-flex flex-wrap align-items-center gap-2" id="activeFiltersList">
                </div>
                <button type="button" class="btn-clear-all-chips" id="btnLimpiarTodoChips" title="Quitar todos los filtros">
                    Limpiar todo
                </button>
            </div>

            <div class="table-responsive">
                <table id="tabla-proveedores" class="table table-striped table-bordered table-sm dt-responsive nowrap" style="width:100%">
                    <thead class="custom-thead">
                        <tr>
                            <th style="width: 13%;" class="text-center">Nro. Acreedor</th>
                            <th style="width: 20%;" class="text-center">Razón Social</th>
                            <th style="width: 14%;" class="text-center">Tipo Proveedor</th>
                            <th style="width: 27%;" class="text-center">Negociador Encargado</th>
                            <th style="width: 14%;" class="text-center">Estado</th>
                            <th style="width: 12%;" class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include '_filtrosProveedor.php'; ?>

    <?php include '../Shared/footer.php'; ?>

    <script src="../../../../../public/js/utils/libs/jquery.js"></script>
    <script src="../../../../../public/js/utils/bootstrap-notify.min.js"></script>
    <script src="../../../../../public/js/utils/notify-animations.js"></script>
    <script src="../../../../../public/js/utils/notify.js"></script>
    <script src="../../../../../public/js/utils/libs/bootstrap.js"></script>
    <script src="../../../../../public/js/utils/libs/datatables.js"></script>
    <script src="../../../../../public/js/utils/libs/fancybox.js"></script>
    <script src="../../../../../public/js/utils/libs/notification.js"></script>
    <!-- JS Choices -->
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <!-- Scripts funcionalidades -->
    <script src="../../../../../public/js/utils/spinner.js"></script>
    <script src="../../../../../public/js/utils/utilidades.js"></script>
    <script src="../../../../../public/js/utils/notifications.js"></script>
    <script src="../../../../../public/js/Administrador/filtrosProveedor.js"></script>
    <script src="../../../../../public/js/Administrador/proveedores.js"></script>

</body>

</html>