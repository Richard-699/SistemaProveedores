<?php

namespace App\Shared\Validation;

use Exception;
use App\Domain\DTO\Auth\UsuariosDTO;

class Validator
{
    public static function validateDTO(object $dto): array
    {
        switch (true) {
            case $dto instanceof UsuariosDTO:
                return self::validateUsuariosDTO($dto);
            default:
                throw new Exception('No hay reglas de validación definidas para este DTO.');
        }
    }

    private static function validateUsuariosDTO(UsuariosDTO $dto): array
    {
        $errors = [];

        if (empty($dto->nombre_usuario)) {
            $errors['inputNombre'] = 'El nombre es obligatorio.';
        }
        if (empty($dto->apellidos_usuario)) {
            $errors['inputApellidos'] = 'Los apellidos son obligatorios.';
        }
        if (empty($dto->correo_usuario)) {
            $errors['inputCorreo'] = 'El correo electrónico es obligatorio.';
        } elseif (!filter_var($dto->correo_usuario, FILTER_VALIDATE_EMAIL)) {
            $errors['inputCorreo'] = 'El correo electrónico no es válido.';
        }
        if (empty($dto->id_area_usuario)) {
            $errors['id_area_usuario'] = 'El área es obligatoria.';
        }
        if (empty($dto->password_usuario)) {
            $errors['inputPassword'] = 'La contraseña es obligatoria.';
        } elseif (strlen($dto->password_usuario) < 8) {
            $errors['inputPassword'] = 'La contraseña debe tener mínimo 8 caracteres.';
        } elseif (!preg_match('/[A-Z]/', $dto->password_usuario)) {
            $errors['inputPassword'] = 'La contraseña debe contener al menos una letra mayúscula.';
        } elseif (!preg_match('/[\W_]/', $dto->password_usuario)) {
            $errors['inputPassword'] = 'La contraseña debe contener al menos un carácter especial.';
        }
        if (empty($dto->password_confirmacion)) {
            $errors['confirmPassword'] = 'La confirmación de contraseña es obligatoria.';
        } elseif ($dto->password_usuario !== $dto->password_confirmacion) {
            $errors['confirmPassword'] = 'Las contraseñas no coinciden.';
        }

        return $errors;
    }
}
