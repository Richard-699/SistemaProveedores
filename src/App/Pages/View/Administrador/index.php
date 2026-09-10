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
    <title>Inicio - Sistema Proveedores</title>

    <link rel="shortcut icon" href="../../../../../public/img/LogoBlanco.png">
    <link rel="stylesheet" href="../../../../../public/css/libs/vendor.bundle.css">
    <link rel="stylesheet" href="../../../../../public/css/Administrador/index.css?v=<?php echo time(); ?>">
</head>

<body>
    <?php include '../Shared/header.php'; ?>
    <div class="video-wrapper">
        <video class="video-principal" autoplay loop muted playsinline>
            <source src="../../../../../public/video/Video Project.mp4" type="video/mp4">
            Tu navegador no soporta el formato de video.
        </video>
    </div>
    <?php include '../Shared/footer.php'; ?>

</body>

</html>