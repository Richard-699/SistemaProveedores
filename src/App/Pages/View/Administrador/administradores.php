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
    <title>Administradores</title>

    <link rel="shortcut icon" href="../../../../../public/img/LogoBlanco.png">
    <link rel="stylesheet" href="../../../../../public/css/libs/vendor.bundle.css">
    <link rel="stylesheet" href="../../../../../public/css/libs/choices.min.css">
    <link rel="stylesheet" href="../../../../../public/css/Administrador/administradores.css">
    <link rel="stylesheet" href="../../../../../public/css/Administrador/aprobarAdministrador.css">
    <link rel="stylesheet" href="../../../../../public/css/Administrador/editarAdministrador.css">
    <link rel="stylesheet" href="../../../../../public/css/Administrador/eliminarAdministrador.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0.16/dist/fancybox.css">
</head>

<body>

    <?php include '../Shared/header.php'; ?>

    <div class="container-fluid px-3 px-md-4 py-3">
        <div class="table-container">
            <div class="d-flex align-items-center mb-4 border-bottom pb-3 mt-1">
                <i class="fa-solid fa-users-gear me-2 fs-4"></i>
                <h5 class="m-0 fw-semibold text-dark">Administradores</h5>
            </div>

            <div class="table-responsive">
                <table id="tabla-administradores" class="table table-striped table-bordered table-sm dt-responsive nowrap" style="width:100%">
                    <thead class="custom-thead">
                        <tr>
                            <th style="width: 10%;" class="text-center">ID</th>
                            <th style="width: 30%;" class="text-center">Administrador</th>
                            <th style="width: 30%;" class="text-center">Correo Corporativo</th>
                            <th style="width: 15%;" class="text-center">Área</th>
                            <th style="width: 15%;" class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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
    <script src="../../../../../public/js/utils/notifications.js"></script>
    <script src="../../../../../public/js/Administrador/administradores.js"></script>

</body>

</html>