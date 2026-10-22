<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\PartNumbers;
use App\Aplication\Interface\Repository\IPartNumbersRepository;
use PDO;

class PartNumbersRepository implements IPartNumbersRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM proveedores_hwi_partnumbers ORDER BY id_partnumber DESC");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([PartNumbers::class, 'fromArray'], $rows);
    }

    public function findById(int $id): ?PartNumbers
    {
        $stmt = $this->db->prepare("SELECT * FROM proveedores_hwi_partnumbers WHERE id_partnumber = :id LIMIT 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? PartNumbers::fromArray($row) : null;
    }

    public function findByPartNumber(string $partnumber): ?PartNumbers
    {
        $stmt = $this->db->prepare("SELECT * FROM proveedores_hwi_partnumbers WHERE partnumber = :partnumber LIMIT 1");
        $stmt->bindParam(':partnumber', $partnumber);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? PartNumbers::fromArray($row) : null;
    }

    public function save(PartNumbers $partNumber): bool
    {
        try {
            $data = $partNumber->toArray();
            if (empty($data['id_partnumber'])) {
                unset($data['id_partnumber']);
            }
            $columns = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_map(fn($key) => ':' . $key, array_keys($data)));

            $stmt = $this->db->prepare(
                "INSERT INTO proveedores_hwi_partnumbers ($columns) VALUES ($placeholders)"
            );

            foreach ($data as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }

            return $stmt->execute();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function updateById(PartNumbers $partNumber): bool
    {
        try {
            $data = $partNumber->toArray();
            $id = $partNumber->id_partnumber;
            unset($data['id_partnumber']);

            $setClause = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));

            $stmt = $this->db->prepare(
                "UPDATE proveedores_hwi_partnumbers SET $setClause WHERE id_partnumber = :id_partnumber"
            );

            foreach ($data as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            $stmt->bindValue(':id_partnumber', $id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function deleteById(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM proveedores_hwi_partnumbers WHERE id_partnumber = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
