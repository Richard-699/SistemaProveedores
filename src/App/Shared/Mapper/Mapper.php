<?php

namespace App\Shared\Mapper;

use App\Domain\DTO\Auth\UsuariosDTO;
use App\Domain\Model\Auth\User;

class Mapper {

    public static function modelToUsuariosDTO(User $user): UsuariosDTO {
        return new UsuariosDTO(
            nombre_usuario: $user->nombre_usuario,
            apellidos_usuario: $user->apellidos_usuario,
            correo_usuario: $user->correo_usuario,
            password_usuario: $user->password_usuario,
            id_usuario: $user->id_usuario,
            estado_registro: $user->estado_usuario
        );
    }

    public static function usuariosDTOToModel(UsuariosDTO $dto): User {
        return new User(
            nombre_usuario: $dto->nombre_usuario,
            apellidos_usuario: $dto->apellidos_usuario,
            correo_usuario: $dto->correo_usuario,
            area_pertenece: (string)($dto->id_area_usuario ?? ''),
            password_usuario: $dto->password_usuario,
            id_usuario: $dto->id_usuario,
            estado_usuario: $dto->estado_registro,
            is_temporal: $dto->is_temporal
        );
    }
}
