<?php

namespace App\Aplication\Interface\Service;

use App\Domain\DTO\AdministradoresDTO;
use App\Domain\DTO\AdministradoresPermisosDTO;

interface IAdministradoresService
{
    public function aprobarAdministrador(AdministradoresPermisosDTO $adminDTO): bool;
    public function rechazarAdministrador(string $idAdministrador): bool;
    public function eliminarAdministrador(string $idAdministrador, ?string $idNuevoAdministrador = null): bool;
    public function actualizarPermisosAdministrador(AdministradoresPermisosDTO $adminDTO): bool;
    public function obtenerAdministradorPorId(string $idAdmin): AdministradoresDTO;
    public function obtenerAdministradores(): array;
    public function obtenerPermisos(): array;
    public function obtenerPermisosAdmin(string $idAdministrador): AdministradoresPermisosDTO;
}
