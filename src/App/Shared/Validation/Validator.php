<?php

namespace App\Shared\Validation;

use Exception;
use App\Domain\DTO\AdministradoresDTO;
use App\Domain\DTO\LoginDTO;
use App\Domain\DTO\ResetPasswordDTO;
use App\Domain\DTO\ChangePasswordDTO;

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
            case $dto instanceof ResetPasswordDTO:
                self::validateResetPasswordDTO($dto);
                break;
            case $dto instanceof ChangePasswordDTO:
                self::validateChangePasswordDTO($dto);
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

    private static function validateResetPasswordDTO(ResetPasswordDTO $dto): void
    {
        if (empty($dto->correo)) {
            throw new Exception('El correo electrónico es obligatorio.');
        } elseif (!filter_var($dto->correo, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('El correo electrónico no es válido.');
        }
    }

    private static function validateChangePasswordDTO(ChangePasswordDTO $dto): void
    {
        if (empty($dto->nuevaPassword)) {
            throw new Exception('La contraseña es obligatoria.');
        } elseif (strlen($dto->nuevaPassword) < 8) {
            throw new Exception('La contraseña debe tener mínimo 8 caracteres.');
        } elseif (!preg_match('/[A-Z]/', $dto->nuevaPassword)) {
            throw new Exception('La contraseña debe contener al menos una letra mayúscula.');
        } elseif (!preg_match('/[\W_]/', $dto->nuevaPassword)) {
            throw new Exception('La contraseña debe contener al menos un carácter especial.');
        }

        if (empty($dto->confirmPassword)) {
            throw new Exception('La confirmación de contraseña es obligatoria.');
        } elseif ($dto->nuevaPassword !== $dto->confirmPassword) {
            throw new Exception('Las contraseñas no coinciden.');
        }
    }
}
