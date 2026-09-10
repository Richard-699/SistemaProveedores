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
    <title>Editar Administrador</title>
</head>
<body>
<div id="content-gestionar-administrador">
    <div class="px-3 py-2" style="width: 600px; max-width: 100%;">
        <div class="border-0 pb-3 mb-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="m-0 fw-semibold">
                <i class="fa-solid fa-pencil me-2"></i>Editar Permisos Administradores
            </h5>
            <button data-fancybox-close class="btn-close" type="button" aria-label="Close"></button>
        </div>
        
        <form id="formEditarAdministrador">
            <input type="hidden" id="admin_id_gestionar" name="id_administrador">
            <input type="hidden" id="admin_action_type" name="action" value="actualizarPermisosAdministrador">
            
            <div class="mb-5 mt-2 pb-2">
                <label for="selectPermisos" class="form-label mb-3">Edite los permisos de este administrador: <span class="text-danger">*</span></label>
                <select class="form-select" id="selectPermisos" name="permisos[]" multiple="multiple" style="width: 100%;">
                    <!-- Opciones cargadas dinámicamente desde js (Session Storage) -->
                </select>
            </div>
        <div class="border-0 pt-3 mt-4 text-end">
            <button type="button" data-fancybox-close class="btn btn-secondary me-2">Cancelar</button>
            <button type="submit" class="btn btn-custom-teal" id="btnEditarAdministrador">Guardar Cambios</button>
        </div>
        </form>
    </div>
</div>

<script src="../../../../../public/js/Administrador/editarAdministrador.js"></script>
</body>
</html>
