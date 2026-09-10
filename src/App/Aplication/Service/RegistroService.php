<?php

namespace App\Aplication\Service;

use App\Aplication\Interface\Service\IRegistroService;
use App\Domain\DTO\AdministradoresDTO;
use App\Shared\Mapper\Mapper;
use App\Shared\Util\Utils;
use App\Infrastructure\Repository\AdministradoresRepository;
use App\Infrastructure\Database\Connection;

class RegistroService implements IRegistroService
{
    private $db;
    private $adminRepository;

    public function __construct()
    {
        $this->db = (new Connection())->dbsistemas_proveedores;
        $this->adminRepository = new AdministradoresRepository($this->db);
    }

    public function validar_email_registrado(string $email): bool
    {
        $count = $this->adminRepository->findByEmail($email);
        return $count > 0;
    }

    public function guardar_administrador(AdministradoresDTO $administradorDTO): bool
    {
        try {
            if ($this->validar_email_registrado($administradorDTO->correo_hwi_administrador)) {
                throw new \Exception("Este usuario ya se encuentra registrado.");
            }

            $administrador = Mapper::administradoresDTOToModel($administradorDTO);
            $guardado = $this->adminRepository->save($administrador);

            if (!$guardado) {
                throw new \Exception("Error al registrar, intente nuevamente.");
            }

            $titulo = "Registro Exitoso - Pendiente de Aprobación";
            $asunto = "Registro en Sistema de Proveedores";
            $nombreCompleto = trim($administradorDTO->nombre_administrador . ' ' . $administradorDTO->apellidos_administrador);
            $contenidoHtml = "Hola " . $nombreCompleto . ",<br><br>"
                . "Tu registro en el Sistema de Proveedores de Haceb Whirlpool Industrial S.A.S. se ha completado de manera exitosa.<br><br>"
                . "Actualmente tu cuenta está <b>Pendiente de Aprobación</b>. Debes esperar a que un administrador del sistema apruebe tu registro para poder ingresar.<br><br>"
                . "Una vez tu cuenta sea activada, recibirás una notificación.<br><br>";

            $correosStrings = [$administradorDTO->correo_hwi_administrador];
            $enviado = Utils::enviarCorreo($correosStrings, $asunto, $titulo, $contenidoHtml);
            
            if (!$enviado) {
                throw new \Exception("Usuario registrado, pero hubo un error al enviar el correo de notificación al nuevo usuario.");
            }

            // Notificar a los Super Administradores
            $superAdminsModels = $this->adminRepository->findAdministradorByIdPermisoandIdEstado(4, 4);
            if (!empty($superAdminsModels)) {
                $superAdminsDTOs = array_map(fn($admin) => Mapper::modelToAdministradoresDTO($admin), $superAdminsModels);
                $superAdminsCorreos = array_map(fn($dto) => $dto->correo_hwi_administrador, $superAdminsDTOs);

                $tituloSuperAdmin = "Nuevo Administrador Pendiente de Aprobación";
                $asuntoSuperAdmin = "Nuevo Registro - Sistema de Proveedores";
                $contenidoHtmlSuperAdmin = "Hola,<br><br>"
                    . "Se ha registrado un nuevo administrador en el Sistema de Proveedores de Haceb Whirlpool Industrial S.A.S.<br><br>"
                    . "<b>Datos del nuevo usuario:</b><br>"
                    . "Nombre: " . $nombreCompleto . "<br>"
                    . "Correo: " . $administradorDTO->correo_hwi_administrador . "<br><br>"
                    . "Por favor, ingresa al sistema para revisar, aprobar o rechazar su solicitud y otorgarle los permisos correspondientes.<br><br>";

                Utils::enviarCorreo($superAdminsCorreos, $asuntoSuperAdmin, $tituloSuperAdmin, $contenidoHtmlSuperAdmin);
            }

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
