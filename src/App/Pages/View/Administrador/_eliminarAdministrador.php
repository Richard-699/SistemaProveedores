<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inactivar Administrador</title>
    <link rel="stylesheet" href="../../../../../public/css/Administrador/eliminarAdministrador.css">
</head>

<body>
    <div id="content-gestionar-administrador">
        <div class="px-3 py-2" style="width: 760px; max-width: 100%;">
            <div class="border-0 pb-3 mb-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="m-0 fw-semibold text-dark">
                    <i class="fa-solid fa-user-slash me-2 text-dark"></i>Inactivar Administrador
                </h5>
                <button data-fancybox-close class="btn-close" type="button" aria-label="Close"></button>
            </div>

            <form id="formEliminarAdministrador">
                <input type="hidden" id="admin_id_eliminar" name="id_administrador">
                <input type="hidden" id="admin_action_type" name="action" value="eliminarAdministrador">

                <div class="alert alert-warning d-flex align-items-center mb-4 py-2" id="alertaTransferencia" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2 fs-5"></i>
                    <div style="font-size: 0.92rem;" id="textoAlertaTransferencia">
                        Al inactivar este administrador, todos los proveedores a su cargo serán transferidos al nuevo administrador seleccionado.
                    </div>
                </div>

                <div class="mb-4 mt-2 pb-2">
                    <label for="selectNuevoAdministrador" class="form-label mb-2" id="labelNuevoAdministrador">Seleccione el administrador que quedará a cargo de los proveedores: <span class="text-danger">*</span></label>
                    <select class="form-select" id="selectNuevoAdministrador" name="id_nuevo_administrador" style="width: 100%;">
                        <option value="">Seleccione un administrador...</option>
                    </select>
                </div>

                <div class="border-0 pt-3 mt-4 text-end">
                    <button type="button" data-fancybox-close class="btn btn-secondary me-2">Cancelar</button>
                    <button type="submit" class="btn btn-custom-teal" id="btnEliminarAdministrador">
                        <i class="fa-solid fa-user-slash me-1"></i> Inactivar Administrador
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="../../../../../public/js/Administrador/eliminarAdministrador.js"></script>
</body>

</html>