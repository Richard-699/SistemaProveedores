<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$permisos = isset($_SESSION['permisos']) ? $_SESSION['permisos'] : [];
$nombre_usuario = isset($_SESSION['nombre_usuario']) ? $_SESSION['nombre_usuario'] : '';
?>
<div class="sidebar collapsed" id="side_nav">
    <div class="header-box px-3 pt-3 pb-4 text-center">
        <div class="logo-container bg-white rounded-3 p-2 mb-2 d-flex justify-content-center align-items-center">
            <img src="../../img/hwiLogo.png" class="img-logo">
        </div>
    </div>

    <ul class="list-unstyled px-2 d-flex flex-column gap-1">
        <li class="mt-4">
            <a href="index.php" class="text-decoration-none px-3 py-2 d-block text-nowrap">
                <i class="material-icons">home</i> <span> Inicio
            </span></a>
        </li><?php if (in_array(1, $permisos)): ?><li class="">
                <a href="../User/registrarProveedor.php" class="text-decoration-none px-3 py-2 d-block text-nowrap">
                    <i class="material-icons">apartment</i> <span> Agregar Proveedor
                </span></a>
            </li>
            <li class="">
                <a href="../User/proveedoresRegistrados.php" class="text-decoration-none px-3 py-2 d-block text-nowrap">
                    <i class="material-icons">groups</i> <span> Proveedores Registrados
                </span></a>
            </li>
            <li class="">
                <a href="../User/vincularProveedor.php" class="text-decoration-none px-3 py-2 d-block text-nowrap">
                    <i class="material-icons">person_add</i> <span> Vinculación Proveedor
                </span></a>
            </li><?php endif; ?>
        <?php if (in_array(2, $permisos)): ?><li class="">
                <a href="../User/registrarPartNumber.php" class="text-decoration-none px-3 py-2 d-block text-nowrap">
                    <i class="material-icons">add</i> <span> Agregar PartNumber
                </span></a>
            </li>
            <li class="">
                <a href="../User/partNumbers.php" class="text-decoration-none px-3 py-2 d-block text-nowrap">
                    <i class="material-icons">precision_manufacturing</i> <span> PartNumbers
                </span></a>
            </li><?php endif; ?>
        
        <?php if (in_array(4, $permisos)): ?><li>
                <a href="../Admin/gestionAccesos.php" class="text-decoration-none px-3 py-2 d-block text-nowrap">
                    <i class="material-icons">key</i> <span> Gestión Accesos
                </span></a>
            </li><?php endif; ?>
        <?php if (in_array(5, $permisos)): ?><li class="">
                <a href="../User/historicoLAFT.php" class="text-decoration-none px-3 py-2 d-block text-nowrap">
                    <i class="material-icons">history</i> <span> Histórico LAFT
                </span></a>
            </li><?php endif; ?>
        
        <?php if (in_array(7, $permisos)): ?><li class="">
                <a href="../User/resultadoCbd.php" class="text-decoration-none px-3 py-2 d-block text-nowrap">
                    <i class="material-icons">description</i> <span> Resultado Cbd
                </span></a>
            </li>
            <li class="">
                <a href="../User/serviciosSuministros.php" class="text-decoration-none px-3 py-2 d-block text-nowrap">
                    <i class="material-icons">design_services</i> <span> Servicios/Suministros
                </span></a>
            </li><?php endif; ?>

        <!-- Proveedores Vinculados (Visible para todos) -->
        <li class="">
            <a href="../User/proveedores.php" class="text-decoration-none px-3 py-2 d-block text-nowrap">
                <i class="material-icons">how_to_reg</i> <span> Proveedores Vinculados
            </span></a>
        </li>
    </ul>

    <div class="sidebar-footer px-2 pb-3 mt-auto">
        <a href="../../Actions/Generals/cerrarsesion.php" class="cerrar-sesion text-decoration-none px-3 py-2 d-block text-nowrap text-white">
            <i class="material-icons">logout</i> <span> Cerrar Sesión
        </span></a>
    </div>
</div>

<div class="content expanded" id="contenido">
    <div class="franja d-flex justify-content-between align-items-center px-4">
        <div class="d-flex align-items-center">
            <button type="button" class="btn btn-menu" id="menu-toggle" onclick="toggleMenu()">
                <i class="material-icons">menu</i> Menú
            </button>
        </div>

        <div class="datos-sesion d-flex align-items-center gap-3">
            <span class="usuario-name fw-bold"><?php echo htmlspecialchars($nombre_usuario); ?></span>
            
            <a href="../../Actions/Generals/cerrarsesion.php" class="nav-icon cerrar-sesion">
                <i class="material-icons">logout</i> <span>
            </span></a>
        </div>
    </div>
   
