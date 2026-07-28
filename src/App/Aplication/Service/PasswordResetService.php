<?php

namespace App\Aplication\Service;

use App\Aplication\Interface\Service\IPasswordResetService;
use App\Aplication\Interface\Repository\IPasswordResetRepository;
use App\Aplication\Interface\Service\IMailService;
use Exception;

class PasswordResetService implements IPasswordResetService {

    private IPasswordResetRepository $repository;
    private IMailService $mailService;

    public function __construct(IPasswordResetRepository $repository, IMailService $mailService) {
        $this->repository = $repository;
        $this->mailService = $mailService;
    }

    public function resetPassword(string $correo): array {
        $correo = trim($correo);

        if (empty($correo)) {
            throw new Exception("El correo es obligatorio.");
        }

        $userData = $this->repository->findUserByEmail($correo);
        
        if (!$userData) {
            throw new Exception("No se encontró ninguna cuenta asociada a este correo.");
        }
        
        // Generar contraseña temporal segura
        $randomStr = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$'), 0, 8);
        $tempPassword = 'Hwi_' . $randomStr;
        $hashedPassword = password_hash($tempPassword, PASSWORD_DEFAULT);
        
        // Actualizar base de datos
        $updated = $this->repository->updateTemporaryPassword($userData['type'], $userData['id'], $hashedPassword);
        
        if (!$updated) {
            throw new Exception("Error al actualizar la contraseña en la base de datos.");
        }

        // Enviar correo
        $mailEnviado = $this->mailService->enviarPasswordTemporal($correo, $tempPassword);
        
        if (!$mailEnviado) {
            throw new Exception("Se actualizó la contraseña pero hubo un error al enviar el correo. Por favor contacte soporte.");
        }
        
        return [
            'status' => 'success', 
            'message' => 'Se ha enviado una contraseña temporal a tu correo.'
        ];
    }
}
