<?php

namespace App\Aplication\Interface\Service;

use App\Domain\DTO\PartNumbersDTO;

interface IPartNumbersService
{
    public function obtenerPartNumbers(): array;
    public function obtenerPartNumberPorId(int $id): PartNumbersDTO;
    public function partNumberExistente(string $partnumber, ?int $idActual = null): bool;
    public function crearPartNumbers(array $partNumbersDTOs): bool;
    public function editarPartNumbers(PartNumbersDTO $partNumberDTO): bool;
    public function eliminarPartNumbers(int $id): bool;
}
