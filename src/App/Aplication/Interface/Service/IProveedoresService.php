<?php

namespace App\Aplication\Interface\Service;

use App\Domain\DTO\ProveedoresDTO;

interface IProveedoresService
{
    /**
     * @return ProveedoresDTO[]
     */
    public function obtenerProveedores(): array;
    /**
     * @return ProveedoresDTO[]
     */
    public function obtenerDatosProveedores(): array;
    public function crearProveedor(ProveedoresDTO $proveedorDTO): bool;
    public function obtenerProveedorPorId(string $idProveedor): ProveedoresDTO;
    public function actualizarProveedor(ProveedoresDTO $proveedorDTO): bool;
    public function cambiarEstadoProveedor(string $idProveedor, int $idEstado): bool;
    public function verificarSimilitudNombre(string $nombre): ?string;
    public function buscarPorNumeroAcreedor(string $numeroAcreedor): bool;
    public function obtenerNegociadoresPorTipo(int $idTipoNegociador): array;
    public function exportarProveedoresAExcel(array $ids): void;
}
