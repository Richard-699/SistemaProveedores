<?php
require_once __DIR__ . '/../../../../../vendor/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['is_admin'])) {
    header("Location: ../Auth/login.php");
    exit();
}

$nombreCompleto = '';
$permisos = [];

if ($_SESSION['is_admin'] && isset($_SESSION['administrador'])) {
    $administrador = $_SESSION['administrador'];
    $nombreCompleto = $administrador->nombre_administrador . ' ' . $administrador->apellidos_administrador;
    $permisos = $administrador->permisosDTO ?? [];
} else if (!$_SESSION['is_admin'] && isset($_SESSION['proveedor'])) {
    $proveedor = $_SESSION['proveedor'];
    $nombreCompleto = $proveedor->nombre_proveedor;
}
?>
<?php
    // Obtener la URL base dinámicamente para enlazar correctamente los estilos
    $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "/SistemaProveedores";
?>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo $baseUrl; ?>/public/css/shared/estilos_header.css">

<div id="sidebar" class="sidebar">
    <a class="fondo-img" href="index.php">
        <img src="../../../../../public/img/LogoBlanco.png" class="img-logo">
    </a>

    <a href="index.php" class="mt-3 hov nav-link"><i class="fas fa-home"></i> Inicio</a>
    <hr>

    <?php
    foreach ($permisos as $permiso) {
        $idPermiso = is_object($permiso) ? $permiso->id_permiso : (is_array($permiso) ? ($permiso['id_permiso'] ?? '') : '');

        switch ($idPermiso) {
            case 1:
                echo '<a class="hov nav-link" href="../Proveedor/proveedores.php"><i class="fas fa-users"></i> Proveedores</a><hr>';
                break;
            case 2:
                echo '<a class="hov nav-link" href="../User/partNumbers.php"><i class="fas fa-robot"></i> PartNumber</a><hr>';
                break;
            case 4:
                echo '<a class="hov nav-link" href="../Administrador/administradores.php"><i class="fas fa-key"></i> Gestión Accesos</a><hr>';
                break;
            case 5:
                echo '<a class="hov nav-link" href="../User/historicoLAFT.php"><i class="fas fa-history"></i> Histórico LAFT</a><hr>';
                break;
            case 6:
                echo '<a class="hov nav-link" href="#"><i class="fas fa-leaf"></i> Formulario Ambiental</a><hr>';
                break;
            case 7:
                echo '<a class="hov nav-link" href="../User/resultadoCbd.php"><i class="fas fa-file-alt"></i> Resultado Cbd</a><hr>';
                echo '<a class="hov nav-link" href="../User/serviciosSuministros.php"><i class="fas fa-pencil-ruler"></i> Servicios/Suministros</a><hr>';
                break;
        }
    }
    ?>

    <a href="#" class="hov" id="BtnCerrarSesion"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
</div>

<div class="content">
    <nav class="navbar navbar-expand-lg navbar-light px-3">
        <button class="menu-toggle" id="menuToggle" onclick="toggleSidebar()">☰ Menú</button>

        <div class="ms-auto d-flex align-items-center">
            <span class="me-3 fw-bold" style="font-size: 0.9rem;"><?php echo htmlspecialchars($nombreCompleto); ?></span>
            <a href="#" id="BtnCerrarSesionMenu" class="text-dark"><i class="fas fa-sign-out-alt fa-lg"></i></a>
        </div>
    </nav>
<script src="<?php echo $baseUrl; ?>/public/js/shared/header.js"></script>