<?php

namespace App\Shared\Validation;

use Exception;
use App\Domain\DTO\AdministradoresDTO;
use App\Domain\DTO\AdministradoresPermisosDTO;
use App\Domain\DTO\LoginDTO;
use App\Domain\DTO\ChangePasswordDTO;
use App\Domain\DTO\ProveedoresDTO;
use App\Domain\DTO\CorreosDTO;
use App\Domain\DTO\PartNumbersDTO;

class Validator
{
    public static function validateDTO(object $dto): void
    {
        switch (true) {
            case $dto instanceof AdministradoresDTO:
                self::validateAdministradoresDTO($dto);
                break;
            case $dto instanceof LoginDTO:
                self::validateLoginDTO($dto);
                break;
            case $dto instanceof ChangePasswordDTO:
                self::validateChangePasswordDTO($dto);
                break;
            case $dto instanceof ProveedoresDTO:
                self::validateProveedoresDTO($dto);
                break;
            case $dto instanceof PartNumbersDTO:
                self::validateEditarPartNumber($dto);
                break;
            default:
                throw new Exception('No hay reglas de validación definidas para este DTO.');
        }
    }

    private static function validateAdministradoresDTO(AdministradoresDTO $dto): void
    {
        if (empty($dto->nombre_administrador)) {
            throw new Exception('El nombre es obligatorio.');
        }
        if (empty($dto->apellidos_administrador)) {
            throw new Exception('Los apellidos son obligatorios.');
        }
        if (empty($dto->correo_hwi_administrador)) {
            throw new Exception('El correo electrónico es obligatorio.');
        } elseif (!filter_var($dto->correo_hwi_administrador, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('El correo electrónico no es válido.');
        }
        if (empty($dto->id_area_administrador)) {
            throw new Exception('El área es obligatoria.');
        }
        if ((int)$dto->id_area_administrador === 1 && empty($dto->id_tipo_negociador)) {
            throw new Exception('Debe seleccionar si es negociador directo o indirecto.');
        }
        if (empty($dto->password_raw)) {
            throw new Exception('La contraseña es obligatoria.');
        } elseif (strlen($dto->password_raw) < 8) {
            throw new Exception('La contraseña debe tener mínimo 8 caracteres.');
        } elseif (!preg_match('/[A-Z]/', $dto->password_raw)) {
            throw new Exception('La contraseña debe contener al menos una letra mayúscula.');
        } elseif (!preg_match('/[\W_]/', $dto->password_raw)) {
            throw new Exception('La contraseña debe contener al menos un carácter especial.');
        }

        if (empty($dto->confirm_password)) {
            throw new Exception('La confirmación de contraseña es obligatoria.');
        } elseif ($dto->password_raw !== $dto->confirm_password) {
            throw new Exception('Las contraseñas no coinciden.');
        }
    }
    private static function validateLoginDTO(LoginDTO $dto): void
    {
        if (empty($dto->usuario)) {
            throw new Exception('El usuario o correo es obligatorio.');
        }
        if (empty($dto->password)) {
            throw new Exception('La contraseña es obligatoria.');
        }
    }

    private static function validateChangePasswordDTO(ChangePasswordDTO $dto): void
    {
        if ($dto->isTemporal === 1) {
            if (empty($dto->usuario)) {
                throw new Exception('El usuario o correo electrónico es obligatorio.');
            } elseif (strpos($dto->usuario, '@') !== false && !filter_var($dto->usuario, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('El correo electrónico no es válido.');
            }
            return;
        }

        if (empty($dto->password_raw)) {
            throw new Exception('La contraseña es obligatoria.');
        } elseif (strlen($dto->password_raw) < 8) {
            throw new Exception('La contraseña debe tener mínimo 8 caracteres.');
        } elseif (!preg_match('/[A-Z]/', $dto->password_raw)) {
            throw new Exception('La contraseña debe contener al menos una letra mayúscula.');
        } elseif (!preg_match('/[\W_]/', $dto->password_raw)) {
            throw new Exception('La contraseña debe contener al menos un carácter especial.');
        }

        if (empty($dto->confirmPassword)) {
            throw new Exception('La confirmación de contraseña es obligatoria.');
        } elseif ($dto->password_raw !== $dto->confirmPassword) {
            throw new Exception('Las contraseñas no coinciden.');
        }
    }

    public static function validatePermisosAdministrador(AdministradoresPermisosDTO $dto): void
    {
        if (empty($dto->id_administrador_permiso)) {
            throw new Exception("ID de administrador requerido.");
        }

        if (empty($dto->permisosDTO)) {
            throw new Exception("Debe seleccionar al menos un permiso.");
        }
    }

    public static function validateIdAdministrador(string $id_admin): void
    {
        if (empty($id_admin)) {
            throw new Exception("ID de administrador requerido.");
        }
    }

    public static function validateEliminarAdministrador(string $id_admin, ?string $id_nuevo_admin = null): void
    {
        if (empty($id_admin)) {
            throw new Exception("ID de administrador a inactivar requerido.");
        }
        if (!empty($id_nuevo_admin) && $id_admin === $id_nuevo_admin) {
            throw new Exception("El administrador seleccionado no puede ser el mismo que se va a inactivar.");
        }
        if (isset($_SESSION['id_usuario']) && $_SESSION['id_usuario'] === $id_admin) {
            throw new Exception("No puede inactivar su propia cuenta de administrador activa.");
        }
    }

    public static function validateProveedor(ProveedoresDTO $dto): void
    {
        if (empty($dto->nombre_proveedor)) {
            throw new Exception("La razón social es obligatoria.");
        }

        if (empty($dto->id_idioma_proveedor) || $dto->id_idioma_proveedor <= 0) {
            throw new Exception("El formato de idioma es obligatorio.");
        }

        if ($dto->id_tipo_proveedor !== 1 && $dto->id_tipo_proveedor !== 2) {
            throw new Exception("El tipo de proveedor es obligatorio (Directo o Indirecto).");
        }

        if (empty($dto->id_administrador_proveedor)) {
            throw new Exception("El negociador encargado es obligatorio.");
        }

        if ($dto->id_tipo_proveedor === 1) {
            if (empty($dto->commodities)) {
                throw new Exception("Debe seleccionar al menos un Commodity para proveedores directos.");
            }
            if ($dto->porcentaje_bom_proveedor < 0 || $dto->porcentaje_bom_proveedor > 100) {
                throw new Exception("El porcentaje de la BOM debe ser un valor numérico entre 0 y 100.");
            }
            if (empty($dto->historia_proveedor)) {
                throw new Exception("La historia del proveedor es obligatoria para proveedores directos.");
            }
            if (empty($dto->descripcion_proveedor)) {
                throw new Exception("La descripción del proveedor es obligatoria para proveedores directos.");
            }
        } elseif ($dto->id_tipo_proveedor === 2) {
            if (empty($dto->id_categoria_proveedor) || $dto->id_categoria_proveedor <= 0) {
                throw new Exception("La categoría es obligatoria para proveedores indirectos.");
            }
            if (empty($dto->id_sub_categoria_proveedor) || $dto->id_sub_categoria_proveedor <= 0) {
                throw new Exception("La sub-categoría es obligatoria para proveedores indirectos.");
            }
        }
    }

    private static function validateProveedoresDTO(ProveedoresDTO $dto): void
    {
        self::validateProveedor($dto);
        self::validateCorreosProveedor($dto->correos);
    }


    public static function validateIdProveedor(?string $idProveedor): void
    {
        if (empty($idProveedor)) {
            throw new Exception("ID de proveedor requerido.");
        }
    }

    public static function validateEditarProveedor(ProveedoresDTO $dto): void
    {
        self::validateIdProveedor($dto->id_proveedor);
        self::validateProveedor($dto);
        self::validateCorreosProveedor($dto->correos);
    }

    public static function validateCambiarEstadoProveedor(?string $idProveedor, int $idEstado): void
    {
        if (empty($idProveedor)) {
            throw new Exception("ID de proveedor requerido.");
        }
        if (!in_array($idEstado, [4, 5])) {
            throw new Exception("Estado no válido.");
        }
    }

    public static function validateCorreosProveedor(array $correos): void
    {
        if (empty($correos)) {
            throw new Exception("Debe ingresar al menos un correo electrónico para el proveedor.");
        }

        foreach ($correos as $item) {
            $correo = $item instanceof CorreosDTO ? $item->correo : (string)$item;
            $c = trim($correo);
            if (empty($c) || !filter_var($c, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("El correo electrónico \"{$c}\" no es válido. Si va a ingresar varios correos, recuerde separarlos por coma (,).");
            }
        }
    }

    public static function validateDatosExportarExcel(mixed $datos): void
    {
        if (empty($datos) || !is_array($datos)) {
            throw new Exception("No hay datos para exportar.");
        }
    }

    public static function validateIdsExportarExcel(mixed $ids): void
    {
        if (empty($ids) || !is_array($ids)) {
            throw new Exception("No se especificaron proveedores para exportar.");
        }

        foreach ($ids as $id) {
            if (empty($id) || !is_string($id)) {
                throw new Exception("Uno o más identificadores de proveedor no son válidos.");
            }
        }
    }

    public static function validateEditarPartNumber(PartNumbersDTO $dto): void
    {
        if (empty($dto->id_partnumber) || $dto->id_partnumber <= 0) {
            throw new Exception("ID de Part Number no válido.");
        }

        if (!empty($dto->extImagenPartNumber)) {
            $permitidas = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array(strtolower($dto->extImagenPartNumber), $permitidas)) {
                throw new Exception("Formato de imagen no permitido. Utilice JPG, PNG o WEBP.");
            }
        }

        if (empty($dto->partnumber)) {
            throw new Exception("El Part Number es obligatorio.");
        }

        if (strlen($dto->partnumber) > 20) {
            throw new Exception("El Part Number no debe superar los 20 caracteres.");
        }

        if (empty($dto->descripcion_partnumber)) {
            throw new Exception("La descripción es obligatoria.");
        }

        if (strlen($dto->descripcion_partnumber) > 300) {
            throw new Exception("La descripción no debe superar los 300 caracteres.");
        }

        if (empty($dto->id_commodity_partnumber)) {
            throw new Exception("Debe seleccionar un Commodity.");
        }

        if ($dto->porcentaje_peso_bom_partnumber === null || $dto->porcentaje_peso_bom_partnumber === '') {
            throw new Exception("El % BOM es obligatorio.");
        }

        if ($dto->porcentaje_peso_bom_partnumber < 0 || $dto->porcentaje_peso_bom_partnumber > 100) {
            throw new Exception("El porcentaje de la BOM debe ser un valor numérico entre 0 y 100.");
        }

        if (round((float)$dto->porcentaje_peso_bom_partnumber, 3) != (float)$dto->porcentaje_peso_bom_partnumber) {
            throw new Exception("El porcentaje de la BOM debe tener como máximo 3 decimales.");
        }
    }

    public static function validateCrearPartNumber(PartNumbersDTO $dto): void
    {
        if (empty($dto->id_proveedor_partnumber)) {
            throw new Exception("Debe seleccionar un Proveedor.");
        }

        if (empty($dto->partnumber)) {
            throw new Exception("El Part Number es obligatorio.");
        }

        if (strlen($dto->partnumber) > 20) {
            throw new Exception("El Part Number no debe superar los 20 caracteres.");
        }

        if (empty($dto->descripcion_partnumber)) {
            throw new Exception("La descripción es obligatoria.");
        }

        if (strlen($dto->descripcion_partnumber) > 300) {
            throw new Exception("La descripción no debe superar los 300 caracteres.");
        }

        if (empty($dto->id_commodity_partnumber) || (int)$dto->id_commodity_partnumber <= 0) {
            throw new Exception("Debe seleccionar un Commodity.");
        }

        if ($dto->porcentaje_peso_bom_partnumber === null || $dto->porcentaje_peso_bom_partnumber === '') {
            throw new Exception("El % BOM es obligatorio.");
        }

        if ($dto->porcentaje_peso_bom_partnumber < 0 || $dto->porcentaje_peso_bom_partnumber > 100) {
            throw new Exception("El porcentaje de la BOM debe ser un valor numérico entre 0 y 100.");
        }

        if (round((float)$dto->porcentaje_peso_bom_partnumber, 3) != (float)$dto->porcentaje_peso_bom_partnumber) {
            throw new Exception("El porcentaje de la BOM debe tener como máximo 3 decimales.");
        }

        if (!empty($dto->extImagenPartNumber)) {
            $permitidas = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array(strtolower($dto->extImagenPartNumber), $permitidas)) {
                throw new Exception("Formato de imagen no permitido. Utilice JPG, PNG o WEBP.");
            }
        }
    }
}
