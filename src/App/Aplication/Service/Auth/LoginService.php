<?php

namespace App\Aplication\Service\Auth;

use App\Aplication\Interface\Service\Auth\ILoginService;
use App\Aplication\Interface\Repository\Auth\ILoginRepository;
use App\Domain\DTO\Auth\LoginDTO;
use Exception;

class LoginService implements ILoginService {

    private ILoginRepository $loginRepository;

    public function __construct(ILoginRepository $loginRepository) {
        $this->loginRepository = $loginRepository;
    }

    public function autenticar(LoginDTO $dto): array {
        
        $usuario = trim($dto->usuario);
        $password = trim($dto->password);

        $isAdmin = (strpos($usuario, '@hacebwhirlpool') !== false || 
                    strpos($usuario, '@haceb') !== false || 
                    strpos($usuario, '@whirlpool') !== false);

        $userData = $this->loginRepository->findUserForAuth($usuario, $isAdmin);

        if (!$userData) {
            throw new Exception("Usuario incorrecto, ingresa nuevamente.");
        }

        $passwordBD = $isAdmin ? $userData['password_administrador'] : $userData['password_proveedor'];
        $isTemporal = $isAdmin ? $userData['password_is_temporal'] : $userData['password_is_temporal_proveedor'];
        $estado = $isAdmin ? $userData['estado_administrador'] : $userData['estado_proveedor'];

        if (!password_verify($password, $passwordBD)) {
            throw new Exception("Contraseña incorrecta, ingresa nuevamente.");
        }

        if ($estado != 4) { 
            throw new Exception("Parece que aún no tienes acceso, solícitalo al administrador e intenta más tarde.");
        }

        $sessionData = [];
        $sessionData['is_temporal'] = $isTemporal;
        $sessionData['is_admin'] = $isAdmin;
        
        if ($isAdmin) {
            $sessionData['id_usuario'] = $userData['id_administrador'];
            $sessionData['nombre_usuario'] = $userData['nombre_administrador'] . ' ' . $userData['apellidos_administrador'];
            $sessionData['correo_usuario'] = $userData['correo_hwi_administrador'];
            $sessionData['Id_area_usuario'] = $userData['id_area_administrador'];
            
            // Área 6 = BI-Admin -> rol 1 (admin), cualquier otra -> rol 2 (negociador)
            $sessionData['id_rol_usuarios'] = ($userData['id_area_administrador'] == 6) ? 1 : 2;
            
            // Obtener los permisos del administrador
            $permisos = $this->loginRepository->findPermissionsByUserId($userData['id_administrador']);
            $sessionData['permisos'] = $permisos;
        } else {
            $sessionData['id_proveedor_usuarios'] = $userData['id_proveedor'];
            $sessionData['nombre_proveedor'] = $userData['nombre_proveedor'];
            $sessionData['correo_usuario'] = $userData['usuario_proveedor'];
            $sessionData['id_rol_usuarios'] = 3; 
        }

        return $sessionData;
    }
}
