<?php

namespace App\Aplication\Interface\Repository;

use App\Domain\Model\PartNumbers;

interface IPartNumbersRepository
{
    public function findAll(): array;
    public function findById(int $id): ?PartNumbers;
    public function findByPartNumber(string $partnumber): ?PartNumbers;
    public function save(PartNumbers $partNumber): bool;
    public function updateById(PartNumbers $partNumber): bool;
    public function deleteById(int $id): bool;
}
