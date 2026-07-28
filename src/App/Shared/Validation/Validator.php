<?php

namespace App\Shared\Validation;

use Exception;

class Validator
{
    public static function validateDTO(object $dto): void
    {
        switch (true) {
            case $dto instanceof \App\Domain\DTO\AdministradoresDTO:
                self::validateAdministradoresDTO($dto);
                break;
            case $dto instanceof \App\Domain\DTO\LoginDTO:
                self::validateLoginDTO($dto);
                break;/*
            case $dto instanceof \App\Domain\DTO\ResetPasswordDTO:
                self::validateResetPasswordDTO($dto);
                break;*/
            default:
                throw new Exception('No hay reglas de validación definidas para este DTO.');
        }
    }

    private static function validateAdministradoresDTO(\App\Domain\DTO\AdministradoresDTO $dto): void
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
        if (empty($dto->password_administrador)) {
            throw new Exception('La contraseña es obligatoria.');
        } elseif (strlen($dto->password_administrador) < 8) {
            throw new Exception('La contraseña debe tener mínimo 8 caracteres.');
        } elseif (!preg_match('/[A-Z]/', $dto->password_administrador)) {
            throw new Exception('La contraseña debe contener al menos una letra mayúscula.');
        } elseif (!preg_match('/[\W_]/', $dto->password_administrador)) {
            throw new Exception('La contraseña debe contener al menos un carácter especial.');
        }
    }
    private static function validateLoginDTO(\App\Domain\DTO\LoginDTO $dto): void
    {
        if (empty($dto->usuario)) {
            throw new Exception('El usuario o correo es obligatorio.');
        }
        if (empty($dto->password)) {
            throw new Exception('La contraseña es obligatoria.');
        }
    }
    /*
    private static function validateResetPasswordDTO(\App\Domain\DTO\ResetPasswordDTO $dto): void
    {
        if (empty($dto->correo)) {
            throw new Exception('El correo electrónico es obligatorio.');
        } elseif (!filter_var($dto->correo, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('El correo electrónico no es válido.');
        }
    }*/
}
